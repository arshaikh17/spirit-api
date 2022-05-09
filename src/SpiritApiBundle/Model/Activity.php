<?php

namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Model\AbstractModel;

/**
 * Object representation of activity data retrieved from SPIRIT. 
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class Activity extends AbstractModel
{
    /**
     * @Mapping(name="CountryId", type="int")
     */
    public $id;

    /**
     * @Mapping(name="ActivityTitle", type="string")
     */
    public $title;

    /**
     * @MappingToModel("Edcoms\SpiritApiBundle\Model\RecordType")
     */
    public $recordType;

    /**
     * @MappingToModel("Edcoms\SpiritApiBundle\Model\WebAccount")
     */
    public $webAccount;
}
