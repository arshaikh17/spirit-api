<?php

namespace Edcoms\SpiritApiBundle\Helper;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Call\MetaDataSaveCall;
use Edcoms\SpiritApiBundle\Caller\ApiCaller;
use Edcoms\SpiritApiBundle\Entity\Interfaces\TagSupportedObjectInterface;
use Edcoms\SpiritApiBundle\Helper\AbstractHelper;
use Edcoms\SpiritApiBundle\Model\Container;
use Edcoms\SpiritApiBundle\Model\MetaData;
use Edcoms\SpiritApiBundle\Model\Tag;
use Edcoms\SpiritApiBundle\Model\Tags;
use Edcoms\SpiritApiBundle\Response\ApiResponse;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Edcoms\SpiritApiBundle\Entity\Interfaces\MetaDataSupportedObjectInterface;

/**
 * Service used to make API calls to the MetaData endpoint on spirit v8.
 *
 * We use this for all meta data related calls (we do not use PersonMetadata endpoints as this is a convenience endpoint derived from metadata endpoints)
 * 
 */
class ContainerHelper extends AbstractHelper
{

    /**
     * Calls the SPIRIT service to list Containers.
     *
     * @param   int  $objectId
     *
     * @return  \Edcoms\SpiritApiBundle\Model\Container[]
     */
    public function listContainers($objectId)
    {

        $apiCall = new ApiCall(
            'GET',
           '/TagContainers/'.$objectId,
            []
        );

        return $this->makeCallAndMapResponse($apiCall);
    }
    
    /**
     * {inheritdoc}
     */
    public function classToMap(): string
    {
        // mapping is managed manually in the underlying class
        return Container::class;
    }
}
