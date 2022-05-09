<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2018-01-04 15:16:15
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-09 18:48:07
 */
namespace Edcoms\SpiritApiBundle\Entity\Interfaces;

interface MetaDataSupportedObjectInterface
{

    //objectId defined in spirit: http://apidocs.educationcompany.co.uk/#object-list
    //ref: why here? https://stackoverflow.com/questions/5350672/pros-and-cons-of-interface-constants
    //these are all spirit v8 object type constants.
    const OBJECTID_ORGANISATION = 1;
    const OBJECTID_PERSON = 2;
    const OBJECTID_APPOINTMENT = 3;
    const OBJECTID_ACTIVITY = 4;
    const OBJECTID_PRODUCT = 5;
    const OBJECTID_ORDER = 6;
    const OBJECTID_COMMUNICATION = 7;
    const OBJECTID_PROMOTION = 8;
    const OBJECTID_DASHBOARD_GENERAL_REPORT = 9;
    const OBJECTID_DASHBOARD_ACTIVITY_REPORT = 10;
    const OBJECTID_DASHBOARD_ORDER_REPORT = 11;
    const OBJECTID_TASK = 12;
    const OBJECTID_QUOTATION = 13;
    const OBJECTID_QUICKLIST = 14;
    const OBJECTID_WEB_ACCOUNT = 15;
    const OBJECTID_SALES_TRANSACTIONS_HEADER = 16;
    const OBJECTID_SALES_TRANSACTIONS_LINE = 17;
    const OBJECTID_SALES_ACCOUNT = 18;
    const OBJECTID_SUBSCRIPTION = 19;
    const OBJECTID_ASSOCIATE = 20;
    const OBJECTID_USER = 21;
    const OBJECTID_ACTION = 22;
    const OBJECTID_EVENT = 23;
    const OBJECTID_EMS = 24;
    const OBJECTID_NOTE = 25;
    const OBJECTID_PIPELINE = 26;
    const OBJECTID_ORDERLINE = 27;
    const OBJECTID_QUOTELINE = 28;
    const OBJECTID_PRODUCTUSAGE = 36;



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
}
