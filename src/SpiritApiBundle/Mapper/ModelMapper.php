<?php

namespace Edcoms\SpiritApiBundle\Mapper;

use Doctrine\Common\Annotations\Reader;
use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Event\SpiritEvents;
use Edcoms\SpiritApiBundle\Event\SpiritMappedEvent;
use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Mapper\Annotation\MappingToModel;
use Edcoms\SpiritApiBundle\Model\AbstractModel;
use Edcoms\SpiritApiBundle\Response\ApiResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Service which creates model objects from the data contained in an ApiResponse object.
 *
 * @author  James Stubbs <james.stubs@edcoms.co.uk>
 */
class ModelMapper
{
    /**
     * @var  Reader
     */
    protected $annotationReader;

    /**
     * @var  EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param  Reader                    $annotationReader  The annotation reader service.
     * @param  EventDispatcherInterface  $eventDispatcher   The event dispatcher service.
     */
    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher)
    {
        $this->annotationReader = $annotationReader;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * The response of the API call is then mapped into model objects,
     * making instance(s) of the class specified in the return of the method 'classToMap()'.
     *
     * @param   ApiResponse  $apiResponse      Contains data to map the new model objects from.
     * @param   string       $modelClass       Class name of the model instances to create.
     *
     * @return  AbstractModel|AbstractModel[]  The object(s) mapped from the response object.
     * @throws  Exception                      If the response object has been marked that it contains errors.
     */
    public function mapFromResponse(ApiResponse $apiResponse, string $modelClass)
    {
        if ($apiResponse->getIsError()) {
            throw new \Exception('Cannot map models from the response as it contains errors.');
        }

        $data = $apiResponse->getData();

        //for some reason SPIRIT API decided to return ProductUsageTransactionType & MetaData response in different structure to other load all endpoints. 
        //They nest the transactiontypes in a child array within the property TransactionTypes.
        if ($modelClass == 'Edcoms\SpiritApiBundle\Model\ProductUsageTransactionType') {
            $data = $data->TransactionTypes;
        }
        //They nest the response within an array even thought it would never r.
        if ($modelClass == 'Edcoms\SpiritApiBundle\Model\MetaData') {
            $data = $data[0];
        }               

        if ($data === null) {
            return [];
        }

        $event = new SpiritMappedEvent();
        $result = null;

        // return the single object if the response data is not an array.
        if (!is_array($data)) {
            $result = $this->createMappedObject($data, $modelClass);

            $event->addModel($result);
        } else {
            $result = [];

            foreach ($data as $objData) {
                $result[] = $this->createMappedObject($objData, $modelClass);
            }

            $event->addModels($result);
        }

        // dispatch the SPIRIT mapped event.
        $this->eventDispatcher->dispatch(SpiritEvents::MAPPED, $event);

        return $result;
    }


    /**
     * Json is mapped into model objects,
     * making instance(s) of the class specified in the return of the method 'classToMap()'.
     *
     * @param   ApiResponse  $apiResponse      Contains data to map the new model objects from.
     * @param   string       $modelClass       Class name of the model instances to create.
     *
     * @return  AbstractModel|AbstractModel[]  The object(s) mapped from the response object.
     * @throws  Exception                      If the response object has been marked that it contains errors.
     */
    public function mapFromJson(string $json, string $modelClass)
    {
        // TODO: consider this piece of functionality.
        // The second 'if' statement below is dependent on whether '$data' is an array or not.
        // The 'json_decode' method will always decode a JSON string into an object instead of an array,
        // because the 2nd parameter is 'false' by default (see http://php.net/manual/en/function.json-decode.php).
        // Therefore, the first part of the 'if' statement where '!is_array($data)' will always result in 'true'.
        $data = json_decode($json);

        if ($data === null) {
            return [];
        }

        $result = null;

        // return the single object if the response data is not an array.
        if (!is_array($data)) {
            $result = $this->createMappedObject($data, $modelClass);
        } else {
            $result = [];

            foreach ($data as $objData) {
                $result[] = $this->createMappedObject($objData, $modelClass);
            }
        }
        
        return $result;
    }

    /**
     * Creates an instance of the class named in the variable '$class'.
     * The model class is then read for any '@Mapping' annotations,
     * which indicate which property of the class should have which value from '$data'. 
     * Each class property is iterated and the value is set from '$data' dependent on how the annotaton is set.
     *
     * @param   mixed   $data   The data to map from.
     * @param   string  $class  Class name of the model instances to create
     *
     * @return  AbstractModel   The newly-mapped model object.
     */
    protected function createMappedObject(\stdClass $data, string $class): AbstractModel
    {
        if (is_a($class, AbstractModel::class)) {
            throw new \InvalidArgumentException(
                "The class '$class' does not inherit the abstract class of '" . AbstractModel::class . '\''
            );
        }

        $mappedObject = new $class();
        $properties = $class::getPublicProperties();

        // iterate through each class property and analyse the annotation.
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $setterMethod = 'set' . ucfirst($propertyName);
            $isUserDefinedField = false;

            $annotation = $this->annotationReader->getPropertyAnnotation(
                $property,
                Mapping::class
            );

            // if the current property is not annotated with the Mapping annotation,
            // we'll ignore it as we will assume it's nothing to do with the mapping data.
            if (null === $annotation) {
                $annotation = $this->annotationReader->getPropertyAnnotation(
                    $property,
                    MappingToModel::class
                );

                if ($annotation !== null) {
                    $childObj = $this->createMappedObject($data, $annotation->getClass());

                    $mappedObject->{$setterMethod}($childObj);
                }

                continue;
            }

            $name = $annotation->getName();

            if (!property_exists($data, $name)) {
                $cont = true;

                //in userDefinedFields.
                if (property_exists($data,'UserDefinedFields')) {
                    if ($data->UserDefinedFields !== null) {
                        $userDefinedFields = $data->UserDefinedFields;

                        if (property_exists($userDefinedFields, $name)) {
                            $isUserDefinedField = 'UserDefinedFields';
                            $cont = false;
                        }
                    }
                }

                //other names.
                $otherNames = explode('|', ($annotation->getOtherNames() ?: ''));

                foreach ($otherNames as $otherName) {
                    if (property_exists($data, $otherName)) {
                        $name = $otherName;
                        $cont = false;
                        break;
                    }
                }

                if ($cont) {
                    continue;
                }
            }

            $type = $annotation->getType();

            // set the value of the property.
            if ($isUserDefinedField) {
                $mappedObject->{$setterMethod}($data->{$isUserDefinedField}->{$name});
            } else {
                $mappedObject->{$setterMethod}($data->{$name});
            }
        }

        return $mappedObject;
    }
}
