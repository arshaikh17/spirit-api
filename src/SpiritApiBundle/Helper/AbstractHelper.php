<?php

namespace Edcoms\SpiritApiBundle\Helper;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Caller\ApiCaller;
use Edcoms\SpiritApiBundle\Caller\ApiCallerInterface;
use Edcoms\SpiritApiBundle\Mapper\ModelMapper;
use Edcoms\SpiritApiBundle\Model\AbstractModel;
use Edcoms\SpiritApiBundle\Normalizer\ModelNormalizer;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;

/**
 * A base helper class which has useful methods to make API calls via the API caller service.
 *
 * Any subclass of this abstract class should be responsible for making API calls
 * relevent only to their defined mapping class (see the 'classToMap()' method).
 *
 * @see  http://apidocs.educationcompany.co.uk For the SPIRIT API documentation.
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
abstract class AbstractHelper
{
    /**
     * @var  ApiCaller
     */
    protected $apiCaller;

    /**
     * @var  ModelMapper
     */
    protected $mapper;

    /**
     * @var  ModelNormalizer
     */
    protected $normalizer;

    /**
     * @var  array
     */
    protected $options;

    /**
     * @var  bool
     */
    protected $throwExceptionOnError = true;

    /**
     * @param  ApiCallerInterface  $apiCaller              The API calling service.
     * @param  ModelMapper         $mapper                 The model mapping service.
     * @param  ModelNormalizer     $normalizer             The model normalizing service.
     * @param  bool                $throwExceptionOnError  Set throw exception on error.
     * @param  array               $options                A collection of helper specific options specified in the configuration.
     */
    public function __construct(
        ApiCallerInterface $apiCaller,
        ModelMapper $mapper,
        ModelNormalizer $normalizer,
        bool $throwExceptionOnError = true,
        array $options = []
    ) {
        $this->apiCaller = $apiCaller;
        $this->mapper = $mapper;
        $this->normalizer = $normalizer;
        $this->options = $options;
        $this->throwExceptionOnError = $throwExceptionOnError;
    }

    /**
     * @param  bool  $throwExceptionOnError  Set throw exception on error.
     */
    public function setThrowExceptionOnError(bool $throwExceptionOnError)
    {
        $this->throwExceptionOnError = $throwExceptionOnError;
    }

    /**
     * @return  string  Full class of the model class to create instances from an API response.
     */
    abstract public function classToMap(): string;

    /**
     * Get helper specific option.
     *
     * @param   string  $name  Identifier of the option.
     *
     * @return  mixed          The option value, or 'null' if not found.
     */
    protected function getOption(string $name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    /**
     * Makes a SPIRIT API call via the API caller service.
     *
     * @param   ApiCall  $apiCall  Details of the API call to make.
     *
     * @return  ApiResponse        The response of the API call.
     * @throws  Exception          
     */
    protected function makeCall(ApiCall $apiCall)
    {
        $response = $this->apiCaller->makeCall($apiCall, $this->throwExceptionOnError);

        if ($response instanceof BadApiResponse && $this->throwExceptionOnError) {
            throw $response->getException();
        }

        return $response;
    }

    /**
     * Makes a SPIRIT API call via the API caller service.
     * The response of the API call is then mapped into model objects,
     * making instance(s) of the class specified in the return of the method 'classToMap()'.
     *
     * @param   ApiCall  $apiCall  Details of the API call to make.
     *
     * @return  mixed              The objects mapped from the response of the API call.
     */
    protected function makeCallAndMapResponse(ApiCall $apiCall)
    {
        $response = $this->makeCall($apiCall);

        return $this->mapper->mapFromResponse($response, $this->classToMap());
    }

    /**
     * Takes '$model' and sets the default values where possible.
     * This is only applied to the values which are either have not been set or are identical to 'null'.
     *
     * @param   AbstractModel  $model  The model instance to normalize.
     *
     * @return  AbstractModel          The resulting normalized model.
     */
    protected function normalizeModel(AbstractModel $model): AbstractModel
    {
        return $this->normalizer->normalizeModel($model);
    }
}
