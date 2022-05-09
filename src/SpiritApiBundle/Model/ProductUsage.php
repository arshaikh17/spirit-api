<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2017-10-12 13:50:34
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:29:19
 */
namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Mapper\Annotation\MappingToModel;
use Edcoms\SpiritApiBundle\Model\AbstractModel;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncEntity;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncMultipleProperty;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncProperty;


/**
 * Object representation of product usage transaction type data retrieved from SPIRIT.
 *
 * @SyncEntity(entity="Edcoms\SpiritApiBundle\Entity\SpiritProductUsage", modelId="id", autoSync=true)
 *
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class ProductUsage extends AbstractModel
{
    /**
     * @Mapping(name="ProductUsageId", type="int")
     * @SyncProperty(property="spiritId")
     */
    public $id;

    /**
     * @var  Product
     *
     * @MappingToModel("Edcoms\SpiritApiBundle\Model\Product")
     * @SyncProperty(
     *     property="spiritProduct",
     *     subSyncProperty="id",
     *     normalizeEntity={"class"="\Edcoms\SpiritApiBundle\Entity\SpiritProduct", "property"="spiritId"}
     * )
     */
    public $product;

    /**
     * @var  Organisation
     *
     * @MappingToModel("Edcoms\SpiritApiBundle\Model\Organisation")
     * @SyncProperty(
     *     property="spiritOrganisation",
     *     subSyncProperty="id",
     *     normalizeEntity={"class"="\Edcoms\SpiritApiBundle\Entity\SpiritOrganisation", "property"="spiritId"}
     * )
     */
    public $organisation;  

    /**
     * @var  Person
     *
     * @MappingToModel("Edcoms\SpiritApiBundle\Model\Person")
     * @SyncProperty(property="spiritPersonId",subSyncProperty="id")
     */
    public $person;

    /**
     * @var  WebAccount
     *
     * @MappingToModel("Edcoms\SpiritApiBundle\Model\WebAccount")
     * @SyncProperty(
     *     property="spiritUser",
     *     subSyncProperty="id",
     *     normalizeEntity={"class"="\Edcoms\SpiritApiBundle\Entity\SpiritUser", "property"="spiritId"}
     * )
     */    
    public $webAccount;

    /**
     * @var  DateTime
     *
     * @Mapping(name="TransactionDate", type="datetime")
     * @SyncProperty(property="transactionDate")
     */
    public $transactionDate;

    /**
     * @var  integer
     *
     * @Mapping(name="Value", type="integer")
     * @SyncProperty(property="value")
     */
    public $value;

    /**
     * @var  ProductUsageTransactionType
     *
     * @MappingToModel("Edcoms\SpiritApiBundle\Model\ProductUsageTransactionType")
     * @SyncProperty(
     *     property="spiritProductUsageTransactionType",
     *     subSyncProperty="id",
     *     normalizeEntity={"class"="\Edcoms\SpiritApiBundle\Entity\SpiritProductUsageTransactionType", "property"="spiritId"}
     * )
     */
    public $transactionType;

    /**
     * @var  integer
     *
     * @Mapping(name="content", type="integer")
     * @SyncProperty(property="spiritContentId")
     */
    public $contentId;
}
