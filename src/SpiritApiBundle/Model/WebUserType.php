<?php

namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Model\AbstractModel;

/**
 * Object representation of web user type data retrieved from SPIRIT. 
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class WebUserType extends AbstractModel
{
    /**
     * @Mapping(name="WebUserTypeId", type="int")
     */
    public $id;
}
