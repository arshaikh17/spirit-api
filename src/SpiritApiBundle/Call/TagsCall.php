<?php

namespace Edcoms\SpiritApiBundle\Call;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Entity\Interfaces\TagSupportedObjectInterface;
use Edcoms\SpiritApiBundle\Model\MetaData;
use Edcoms\SpiritApiBundle\Model\Tags;

/**
 *
 */
class TagsCall extends ApiCall
{
    /**
     * Creates an ApiCall object, so that it can be used to call the SPIRIT service to save Tags.
     *
     * @param   Tags  $tags  The model object to grab the data from to send to SPIRIT.
     *
     * @return  self  The created ApiCall object.
     */
    public static function save(Tags $tags): self
    {
        $object = $tags->getObject();

        if(!($object instanceof TagSupportedObjectInterface)){
            return new \Exception(sprintf('Tags functionlity is not supported for class "%s" ', get_class($object)));
        }

        $tagsData = $tags->getTagsPayload();

        $apiCall = new self('POST', $object->getTagsEndpointURL(), $tagsData);

        return $apiCall;
    }

    public static function remove(Tags $tags): self
    {
        $object = $tags->getObject();

        if(!($object instanceof TagSupportedObjectInterface)){
            return new \Exception(sprintf('Tags functionlity is not supported for class "%s" ', get_class($object)));
        }

        $tagsDataToDelete = $tags->getDeletedTagsPayload();

        $apiCall = new self('DELETE', $object->getTagsEndpointURL(), $tagsDataToDelete);

        return $apiCall;
    }
}
