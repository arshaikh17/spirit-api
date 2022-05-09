<?php

namespace Edcoms\SpiritApiBundle\Normalizer;

use Doctrine\Common\Annotations\Reader;
use Edcoms\SpiritApiBundle\Mapper\Annotation\MappingToModel;
use Edcoms\SpiritApiBundle\Model\AbstractModel;

/**
 * Model normalizing service which takes a declared set of default values,
 * and then sets any model's property value as the relating default value if it hasn't been previously set.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class ModelNormalizer
{
    /**
     * @var  Reader
     */
    protected $annotationReader;

    /**
     * @var  array
     */
    protected $defaults;

    /**
     * @param  Reader  $annotationReader  The annotation reader service.
     * @param  array   $defaults          The default values for Model classes and relationships.
     */
    public function __construct(Reader $annotationReader, array $defaults = [])
    {
        $this->annotationReader = $annotationReader;
        $this->defaults = $defaults;
    }

    /**
     * Takes '$model' and adds in any defaults stored within the service.
     *
     * @param   AbstractModel  $model  The model instance to normalize.
     *
     * @return  AbstractModel          The resulting normalized model.
     */
    public function normalizeModel(AbstractModel $model): AbstractModel
    {
        return $this->normalizeModelFromDefaults($model);
    }

    /**
     * Takes '$model' and adds in any defaults stored within the service.
     * It also uses defaults from '$additionalDefaults' so that we can use recusion to fetch the defaults for a relationship property.
     *
     * @param   AbstractModel  $model               The model instance to normalize.
     * @param   array          $additionalDefaults  Any aditional defaults to normalize the model with.
     *
     * @return  AbstractModel                       The resulting normalized model.
     */
    protected function normalizeModelFromDefaults(AbstractModel $model, array $additionalDefaults = []): AbstractModel
    {
        $modelClass = get_class($model);
        $properties = $modelClass::getPublicProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $defaultValue = null;

            // grab the default value for the current iterating property.
            if (isset($this->defaults[$modelClass])) {
                // default value is declared within model's class values.
                $defaultValue = $this->defaults[$modelClass];
            } elseif (isset($additionalDefaults[$propertyName])) {
                // odds are this default value is declared within a relationship property in the current model class.
                $defaultValue = $additionalDefaults[$propertyName];
            } else {
                // no default value to normalize property with, so skip.
                continue;
            }

            // grab the model's current value and annotation for the iterating property.
            $value = $model->{$propertyName};
            $annotation = $this->annotationReader->getPropertyAnnotation(
                $property,
                MappingToModel::class
            );

            if ($annotation === null) {
                // if the current value has already been set
                // or if the default value is an array,
                // don't override it.
                if ($value !== null || is_array($defaultValue)) {
                    continue;
                }

                // set the value as default.
                $value = $defaultValue;
            } else {
                $class = $annotation->getClass();

                if (null === $value) {
                    $value = new $class();
                }

                if (is_object($value)) {
                    // as this current iterating property is a relationship, and the default value is a collection of values,
                    // recursively retrieve the default value, then set the current valye as the returned result.
                    $value = $this->normalizeModelFromDefaults(
                        $value,
                        isset($defaultValue[$propertyName]) ? $defaultValue[$propertyName]  : $defaultValue
                    );
                }
            }

            // set the model's property value.
            $model->{$propertyName} = $value;
        }

        return $model;
    }
}
