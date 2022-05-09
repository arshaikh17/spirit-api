<?php

namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Model\AbstractModel;

/**
 * Object representation of record type data retrieved from SPIRIT. 
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class RecordType extends AbstractModel
{
    /**
     * @Mapping(name="RecordTypeId", type="int")
     */
    public $id;
}
