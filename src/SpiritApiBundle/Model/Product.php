<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2017-10-12 13:50:34
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:29:10
 */
namespace Edcoms\SpiritApiBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Mapper\Annotation\MappingToModel;
use Edcoms\SpiritApiBundle\Model\AbstractModel;
use Edcoms\SpiritApiBundle\Model\Product;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncEntity;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncMultipleProperty;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncProperty;

/**
 * Object representation of product data retrieved from SPIRIT.
 *
 * @SyncEntity(entity="Edcoms\SpiritApiBundle\Entity\SpiritProduct", modelId="id", autoSync=true)
 *
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class Product extends AbstractModel
{
    /**
     * @Mapping(name="ProductId", type="int")
     * @SyncProperty(property="spiritId")
     */
    public $id;

    /**
     * @Mapping(name="Name", type="string")
     * @SyncProperty(property="name")
     */
    public $name;

    /**
     * @Mapping(name="Code", type="string")
     * @SyncProperty(property="code")
     * Must be unique.
     */
    public $code;

    /**
     * @Mapping(name="DisplayName", type="string")
     * @SyncProperty(property="displayName")
     */
    public $displayName;

    /**
     * @Mapping(name="LookupCode", type="string")
     * @SyncProperty(property="lookupCode")
     */
    public $lookupCode;

    /**
     * @Mapping(name="Description", type="string")
     * @SyncProperty(property="description")
     */
    public $description;

    /**
     * @Mapping(name="ShowOnWebSite", type="boolean")
     * @SyncProperty(property="showOnWebSite")
     */
    public $showOnWebSite;

    /**
     * @Mapping(name="Digital", type="boolean")
     * @SyncProperty(property="digital")
     */
    public $digital;

    /**
     * @Mapping(name="ContainsDigitalAssets", type="boolean")
     * @SyncProperty(property="containsDigitalAssets")
     */
    public $containsDigitalAssets;

    /**
     * @Mapping(name="Stock", type="boolean")
     * @SyncProperty(property="stock")
     */
    public $stock;

    /**
     * @Mapping(name="AcceptBackOrders", type="boolean")
     * @SyncProperty(property="acceptBackOrders")
     */
    public $acceptBackOrders;

    /**
     * @Mapping(name="PublicationDate", type="date")
     * @SyncProperty(property="publicationDate")
     */
    public $publicationDate;

    /**
     * @Mapping(name="ReprintDate", type="date")
     * @SyncProperty(property="reprintDate")
     */
    public $reprintDate;

    /**
     * @Mapping(name="Subscription", type="boolean")
     * @SyncProperty(property="subscription")
     */
    public $subscription;

    /**
     * @Mapping(name="SubscriptionLengthDays", type="int")
     * @SyncProperty(property="subscriptionLengthDays")
     */
    public $subscriptionLengthDays;

    /**
     * @Mapping(name="Pipeline", type="boolean")
     * @SyncProperty(property="pipeline")
     */
    public $pipeline;

    /**
     * @Mapping(name="Attributes", type="array")
     * @SyncProperty(property="attributes")
     */
    public $attributes;


    public function __construct()
    {
        $this->attributes = array();
    }
}
