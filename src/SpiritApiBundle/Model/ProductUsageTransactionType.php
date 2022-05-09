<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2017-09-26 14:33:49
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:29:25
 */
namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Mapper\Annotation\MappingToModel;
use Edcoms\SpiritApiBundle\Model\AbstractModel;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncEntity;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncMultipleProperty;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncProperty;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Object representation of product usage transaction type data retrieved from SPIRIT.
 *
 * @SyncEntity(entity="Edcoms\SpiritApiBundle\Entity\SpiritProductUsageTransactionType", modelId="id", autoSync=true)
 *
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class ProductUsageTransactionType extends AbstractModel
{
    /**
     * @Mapping(name="TransactionTypeId", otherNames="Id", type="int")
     * @SyncProperty(property="spiritId")
     */
    public $id;

    /**
     * @Mapping(name="Description", type="string")
     */
    public $description;

    /**
     * @Mapping(name="Active", type="boolean")
     *
     */
    public $active;

}
