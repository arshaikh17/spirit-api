<?php

namespace Edcoms\SpiritApiBundle\Helper;

use Edcoms\SpiritApiBundle\Caller\ApiCallerInterface;
use Edcoms\SpiritApiBundle\Synchronizer\EntitySynchronizer;

/**
 * A service containing all SPIRIT API helpers.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class SpiritApiHelpers
{
    /**
     * @var  ApiCallerInterface 
     */
    protected $apiCaller;

    /**
     * @var  AbstractHelper
     */
    protected $helpers = [];

    /**
     * @var  EntitySynchronizer
     */
    protected $synchronizer;

    /**
     * @param  ApiCallerInterface  $apiCaller  The API calling service.
     */
    public function __construct(ApiCallerInterface $apiCaller, EntitySynchronizer $synchronizer)
    {
        $this->apiCaller = $apiCaller;
    }

    /**
     * Add a SPIRIT helper service.
     *
     * @param  string  $name  Name of the helper service to add.
     */
    public function addHelper(AbstractHelper $helper, string $name)
    {
        $this->helpers[$name] = $helper;
    }

    /**
     * Takes the data from '$model' and updates the existing relating record in the database.
     * The record is automatically created if it doesn't exist.
     *
     * @param  AbstractModel  $model  Model to cache to the database.
     */
    public function cacheModel(AbstractModel $model)
    {
        $this->synchronizer->cacheModel($model);
    }

    /**
     * Retrieves the SPIRIT API helper under the name of '$name'.
     *
     * @param   string  $name        Name of the helper service to retrieve.
     *
     * @return  AbstractHelper|null  The SPIRIT API helper.
     */
    public function getHelper(string $name)
    {
        return isset($this->helpers[$name]) ? $this->helpers[$name] : null;
    }

    /**
     * TODO: comment
     */
    public function getAvailableHelperNames(): array
    {
        return array_keys($this->helpers);
    }
}
