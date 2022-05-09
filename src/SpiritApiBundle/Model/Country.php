<?php

namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Model\AbstractModel;

/**
 * Object representation of country data retrieved from SPIRIT. 
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class Country extends AbstractModel
{
    /**
     * @Mapping(name="CountryId", type="int")
     */
    public $id;

    /**
     * @Mapping(name="CountryName", otherNames="Country", type="string")
     */
    public $name;
}
