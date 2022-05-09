<?php

/**
 * @Author: Dimitris <dimitris@edcoms.co.uk>
 * @Date:   2019-05-31 12:44:38z
 * @see  http://uat-phobos.education.co.uk/Service849/docs/#tag/Tags
 */
namespace Edcoms\SpiritApiBundle\Helper;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Call\MetaDataSaveCall;
use Edcoms\SpiritApiBundle\Call\TagsCall;
use Edcoms\SpiritApiBundle\Call\TagsSaveCall;
use Edcoms\SpiritApiBundle\Caller\ApiCaller;
use Edcoms\SpiritApiBundle\Caller\ApiCallerInterface;
use Edcoms\SpiritApiBundle\Entity\Interfaces\TagSupportedObjectInterface;
use Edcoms\SpiritApiBundle\Helper\AbstractHelper;
use Edcoms\SpiritApiBundle\Mapper\ModelMapper;
use Edcoms\SpiritApiBundle\Model\Container;
use Edcoms\SpiritApiBundle\Model\MetaData;
use Edcoms\SpiritApiBundle\Model\Tag;
use Edcoms\SpiritApiBundle\Model\Tags;
use Edcoms\SpiritApiBundle\Normalizer\ModelNormalizer;
use Edcoms\SpiritApiBundle\Response\ApiResponse;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Edcoms\SpiritApiBundle\Entity\Interfaces\MetaDataSupportedObjectInterface;

/**
 * Service used to make API calls to the MetaData endpoint on spirit v8.
 *
 * We use this for all meta data related calls (we do not use PersonMetadata endpoints as this is a convenience endpoint derived from metadata endpoints)
 * 
 */
class TagsHelper extends AbstractHelper
{

    private $containers = [];

    /**
     * @var \Edcoms\SpiritApiBundle\Helper\ContainerHelper
     */
    private $containerHelper;

    /**
     * Calls the SPIRIT service to save Tags.
     *
     * @param   Tag[]  $tags
     *
     * @return  true|BadApiResponse  populated with the the response.
     */
    public function saveTags(Tags $tags)
    {
        if(count($tags->getTags())===0 && $tags->hasItemsToRemove() === false){
            return true;
        }

        $apiCall = TagsCall::save($tags);

        $response = $this->makeCall($apiCall);

        // continues if an exception hasn't already been thrown.
        if ($response instanceof BadApiResponse) {
            return $response;
        }

        if($tags->hasItemsToRemove() === false){
            return $response->getData();
        } else{
            $apiCall = TagsCall::remove($tags);

            $response = $this->makeCall($apiCall);

            // continues if an exception hasn't already been thrown.
            if ($response instanceof BadApiResponse) {
                return $response;
            }

            return $response->getData();
        }
    }

    /**
     * Calls the SPIRIT service to load Tags.
     *
     * @param   TagSupportedObjectInterface  $object    An object (entity in spiritapi bundle) that implements TagSupportedObjectInterface
     *
     * @return  Tags
     */
    public function loadTags(TagSupportedObjectInterface $object)
    {

        $apiCall = new ApiCall(
            'GET',
           '/Tags/'.$object->getObjectId(),
            [],
            [
                'pkvalue' => $object->getObjectPrimaryKey()
            ]
        );

        $tags = new Tags($object, $this->makeCallAndMapResponse($apiCall));

        return $tags;
    }

    /**
     * Calls the SPIRIT service to load Tags by Container.
     *
     * @param   string  $containerId    Container UUID
     *
     * @return  Tags
     */
    public function loadTagsByContainer($containerId)
    {
        $apiCall = new ApiCall(
            'GET',
            sprintf('/TagContainers/%s/Tags', $containerId,
            [],
            [])
        );

        $tags = new Tags($object, $this->makeCallAndMapResponse($apiCall));

        return $tags;
    }

    public function addTag(Tags $tags, string $containerCode, string $tagId){
        if(!isset($this->containers[$containerCode])){
            $this->loadContainers($tags->getObject());
        }

        $container = $this->containers[$containerCode];

        if($container && $container instanceof Container){
            $tag = new Tag();
            $tag->containerName = $container->containerName;
            $tag->tagId = $tagId;
            $tag->containerId = $container->id;
            $tags->addTag($tag);
        }
        return false;
    }

    /**
     * @param \Edcoms\SpiritApiBundle\Model\Tags $tags
     * @param string $containerCode
     * @param string $tagId
     *
     * @return bool
     */
    public function removeTag(Tags $tags, Tag $tag){
        return $tags->removeTag($tag);
    }

    private function loadContainers(TagSupportedObjectInterface $object){
        // cache Containers
        $containers = $this->containerHelper->listContainers($object->getObjectId());

        foreach ($containers as $container){
            if(!isset($this->containers[$container->code])){
                $this->containers[$container->code] = $container;
            }
        }
    }
    
    /**
     * {inheritdoc}
     */
    public function classToMap(): string
    {
        // mapping is managed manually in the underlying class
        return Tag::class;
    }

    public function setContainerHelper(ContainerHelper $containerHelper){
        $this->containerHelper = $containerHelper;
    }
}
