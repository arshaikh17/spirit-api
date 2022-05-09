<?php

namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Entity\Interfaces\TagSupportedObjectInterface;
use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Model\AbstractModel;

/**
 * Class Tags
 *
 * @package Edcoms\SpiritApiBundle\Model
 */
class Tags
{

    /**
     * @var TagSupportedObjectInterface
     */
    private $object;

    /**
     * @var Tag[]
     */
    private $tagItems = [];

    /**
     * @var Tag[]
     */
    private $tagItemsToRemove = [];

    /**
     * Tags constructor.
     *
     * @param $object
     * @param $tagItems
     */
    public function __construct(TagSupportedObjectInterface $object, array $tagItems){
        $this->object = $object;
        foreach ($tagItems as $tagItem){
            $this->addTag($tagItem);
        }
    }

    /**
     * @return \Edcoms\SpiritApiBundle\Model\Tag[]
     */
    public function getTags() {
        return $this->tagItems;
    }

    public function addTag(Tag $tag){
        if(!$this->tagExist($tag)){
            $this->tagItems[] = $tag;
        }
        if($this->tagExist($tag, true)){
            $this->removeTagFromDeletedList($tag);
        }
    }

    /**
     * @param string $containerId
     * @param string $tagId
     *
     * @return bool
     */
    public function removeTag(Tag $tag){
        foreach ($this->tagItems as $key => $tagItem){
            if($tagItem->tagId === $tag->tagId && $tagItem->containerId === $tag->containerId){
                $this->removeTagItem($this->tagItems[$key]);
                unset($this->tagItems[$key]);
                return true;
            }
        }
        return false;
    }

    /**
     * @param \Edcoms\SpiritApiBundle\Model\Tag $tag
     *
     * @return bool
     */
    private function tagExist(Tag $tag, $deletedListLookup=false){
        $lookupList = $deletedListLookup ? $this->tagItemsToRemove : $this->tagItems;

        foreach ($lookupList as $tagItem){
            if($tagItem->containerId === $tag->containerId && $tagItem->tagId === $tag->tagId){
                return true;
            }
        }
        return false;
    }

    /**
     * @return \Edcoms\SpiritApiBundle\Entity\Interfaces\TagSupportedObjectInterface
     */
    public function getObject() {
        return $this->object;
    }

    public function getTagsPayload(){
        $data = [];

        foreach ($this->tagItems as $tagItem){
            $data[] = [
                'TagId' => $tagItem->tagId,
                'ContainerId' => $tagItem->containerId
            ];
        }

        return $data;
    }

    public function getDeletedTagsPayload(){
        $data = [];

        foreach ($this->tagItemsToRemove as $tagItem){
            $data[] = [
                'TagId' => $tagItem->tagId,
                'ContainerId' => $tagItem->containerId,
                'NumberValue' => 0
            ];
        }

        return $data;
    }

    /**
     * @return int
     */
    public function hasItemsToRemove(){
        return count($this->tagItemsToRemove)>0;
    }

    /**
     * @param \Edcoms\SpiritApiBundle\Model\Tag $tag
     */
    private function removeTagItem(Tag $tag){
        $this->tagItemsToRemove[] = $tag;
    }

    /**
     * @param \Edcoms\SpiritApiBundle\Model\Tag $tag
     */
    private function removeTagFromDeletedList(Tag $tag){
        foreach ($this->tagItemsToRemove as $key => $t){
            if($tag->tagId === $t->tagId){
                unset($this->tagItemsToRemove[$key]);
                return true;
            }
        }
    }

}
