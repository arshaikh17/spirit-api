<?php

namespace Edcoms\SpiritApiBundle\Entity\Interfaces;

interface TagSupportedObjectInterface
{
    
    /**
     * Gets the static objectID (which defines the type of object i.e. Person = 2, WebAccount = 15).
     * This will be the necessary data which will be used to save and load MetaData object in the SPIRIT service.
     *
     * @return  integer  spirit objectId (type) of the object.
     */
    public function getObjectId();

    /**
     * Gets a specific object's unique spirit Primary Key (i.e. spiritId = 498d895c-0a57-4c95-83cd-f41d3c07d6e6).
     * This will be the necessary data which will be used to save and load MetaData object in the SPIRIT service.
     *
     * @return  string  spiritId of the object.
     */
    public function getObjectPrimaryKey();

    public function getTagsEndpointURL();
}
