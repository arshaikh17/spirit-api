<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2017-12-19 15:41:51
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-04-18 11:55:45
 */
namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Model\AbstractModel;

/**
 * Object representation of MetaData type data retrieved from SPIRIT. 
 */
class MetaData extends AbstractModel
{
    /**
     * @Mapping(name="objectId", type="int")
     */
    public $objectId;

    /**
     * @Mapping(name="objectPrimaryKey", type="string")
     *
     * This is the spiritId for the given objectID (object type).
     */
    public $objectPrimaryKey;

    /**
     * @Mapping(name="metaDatas", otherNames="MetaDataItems", type="array")
     */
    public $metaDatas = array();    

    /**
     * Add a metaDataItem to metaDatas array property.
     *
     * @param Edcoms\SpiritApiBundle\Model\MetaDataItem $metaDataItem
     *
     * @return MetaData
     */
    /**
     * Add a metaDataItem to metaDatas array property.
     * @param string $fieldName  name of the Metadata field that exists in against the object ype (objectID) in spirit.
     * @param string $fieldValue value of to assign to the metaData field for this 
     */
    public function addMetaDataItem(string $fieldName, string $fieldValue)
    {        
        if ($this->hasMetaDataItem($fieldName)) {            
            return $this;
        }

        //add metaDataItem as array to metaData array.
        $this->metaDatas[] = array(
            'FieldName' => $fieldName,
            'Value' => $fieldValue
        );

        return $this;
    }


    /**
     * Update a metaDataItem already in the metaDatas array property.
     *
     * @param string $fieldName  name of the Metadata field that exists in against the object ype (objectID) in spirit.
     * @param string $fieldValue value of to assign to the metaData field for this 
     *
     * @return MetaData
     */
    public function updateMetaDataItem(string $fieldName, string $fieldValue)
    {
        $key = $this->hasMetaDataItem($fieldName, true);
        if ($key >= 0) {
            $this->metaDatas[$key]->Value = $fieldValue;
        }   

        return $this;
    } 


    /**
     * Check whether a metaDataItem is already in the metaDatas array property.
     *
     * @param Edcoms\SpiritApiBundle\Model\MetaDataItem $metaDataItem
     *
     * @return MetaData
     */
    public function hasMetaDataItem(string $fieldName, $isUpdate = false)
    {
        //init.
        $result = false; 
        $key = null;

        //metaDatas is an array of arrays.
        foreach ($this->metaDatas as $index => $metaData) {
            if ($metaData->FieldName == $fieldName) {
                $result = true;
                $key = $index;
                break;
            }

        }

        //if update and exist, return the matching metaData array.
        if ($isUpdate && $result) {
            return $key;
        }

        if ($result) {
            return true;
        }

        return false;
    } 

    /**
     * Remove a metaDataItem from the metaDatas array property, if it exists.
     *
     * @param Edcoms\SpiritApiBundle\Model\MetaDataItem $metaDataItem
     *
     * @return MetaData
     */
    public function removeMetaDataItem(string $fieldName)
    {
        $key = $this->hasMetaDataItem($fieldName, true);

        //if key exists, remove it.
        if ($key >= 0) {
            unset($this->metaDatas[$key]);
        }        
        
        return $this;        
    }

    /**
     * Set metaDatas property to an array containing all metaData
     *
     * NB: This will replace any existing metaData.
     *
     * @return MetaData
     */
    /**
     * Set all metaDataItems to metaDatas array property.
     * @param array $metaDataItems  array of array (metaDataItems). 
     */
    public function setMetaDatas(array $metaDataItems)
    {
        $this->clearMetaDatas();
        $this->metaDatas = $metaDataItems;

        return $this;
    }     

    /**
     * Remove all metaDataItems from the metaDatas array property.
     *
     * @return MetaData
     */
    public function clearMetaDatas()
    {
        $this->metaDatas = array();        
    }
}
