<?php

namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Mapper\Annotation\MappingToModel;
use Edcoms\SpiritApiBundle\Model\AbstractModel;

/**
 * Object representation of person data retrieved from SPIRIT.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class Person extends AbstractModel
{
    /**
     * @Mapping(name="PersonId", type="int")
     */
    public $id;

    /**
     * @Mapping(name="PersonTitle", type="string")
     */
    public $title;

    /**
     * @Mapping(name="PersonFirstName", type="string")
     */
    public $firstName;

    /**
     * @Mapping(name="PersonLastName", type="string")
     */
    public $lastName;

    /**
     * @Mapping(name="PersonJobTitle", type="string")
     */
    public $jobTitle;

    /**
     * @MappingToModel("Edcoms\SpiritApiBundle\Model\JobType")
     */
    public $jobType;
}
