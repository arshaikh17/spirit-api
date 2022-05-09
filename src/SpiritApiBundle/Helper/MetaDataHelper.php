<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2017-12-19 15:44:38
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-11 14:44:28
 * @see  http://apidocs.educationcompany.co.uk/#metadatas-api-post
 */
namespace Edcoms\SpiritApiBundle\Helper;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Call\MetaDataSaveCall;
use Edcoms\SpiritApiBundle\Caller\ApiCaller;
use Edcoms\SpiritApiBundle\Helper\AbstractHelper;
use Edcoms\SpiritApiBundle\Model\MetaData;
use Edcoms\SpiritApiBundle\Response\ApiResponse;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Edcoms\SpiritApiBundle\Entity\Interfaces\MetaDataSupportedObjectInterface;

/**
 * Service used to make API calls to the MetaData endpoint on spirit v8.
 *
 * We use this for all meta data related calls (we do not use PersonMetadata endpoints as this is a convenience endpoint derived from metadata endpoints)
 * 
 */
class MetaDataHelper extends AbstractHelper
{

    /**
     * Calls the SPIRIT service to save Metadata.
     *
     * @param   MetaData  $metaData    The Metadata to create.
     *
     * @return  Response|BadApiResponse  populated with the the response.
     */
    public function saveMetaData(MetaData $metaData)
    {

        $metaData = $this->normalizeModel($metaData);
        $apiCall = MetaDataSaveCall::Save($metaData);

        $response = $this->makeCall($apiCall);

        // continues if an exception hasn't already been thrown.
        if ($response instanceof BadApiResponse) {
            return $response;
        }

        $response = array(
            'result' => $response->getData(),
            'data'   => $metaData
        );

        return $response;

    }

    /**
     * Calls the SPIRIT service to load Metadata.
     *
     * @param   MetaDataSupportedObjectInterface  $object    An object (entity in spiritapi bundle) that implements MetaDataSupportedObjectInterface
     *
     * @return  Response|BadApiResponse  populated with the the response.
     */
    public function loadMetaData(MetaDataSupportedObjectInterface $object)
    {

        $apiCallData = [
            'objectId' => $object->getObjectId(),
            'objectPrimaryKey' => $object->getObjectPrimaryKey()
        ];

        $apiCall = new ApiCall('GET','/MetaDatas/',$apiCallData);

        $response = $this->makeCallAndMapResponse($apiCall);

        //we do not get the objectId and objectPrimaryKey back in the response from spirit, so these can't be mapped to the MetaDataModel. We do it here manually so we can identify what the metaData response relates to.
        $response->setObjectId($object->getObjectId());
        $response->setObjectPrimaryKey($object->getObjectPrimaryKey());

        return $response;

    }

    /**
     * {inheritdoc}
     */
    public function classToMap(): string
    {
        return MetaData::class;
    }
}
