<?php

namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Model\AbstractModel;

/**
 * Object representation of job type data retrieved from SPIRIT. 
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class JobType extends AbstractModel
{
    /**
     * @Mapping(name="PersonJobTypeId", type="int")
     */
    public $id;

    /**
     * @Mapping(name="PersonType", type="string")
     */
    public $name;
}
