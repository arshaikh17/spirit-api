<?php

namespace Edcoms\SpiritApiBundle\Mapper\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Informs the response mapping functionality which details a response field to a model property.
 *
 * @Annotation
 * @Target("PROPERTY")
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class Mapping
{
    /**
     * @Annotation\Required()
     *
     * @var  string
     */
    public $name;

    /**
     * @var  string
     */
    public $otherNames;

    /**
     * @var  string
     */
    public $type;

    /**
     * @return  string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $name  Name to set.
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return  string  Get other names.
     */
    public function getOtherNames()
    {
        return $this->otherNames;
    }

    /**
     * @param  string  $names  Other names to set.
     */
    public function setOtherNames(string $otherNames)
    {
        $this->otherNames = $otherNames;
    }

    /**
     * @return  string  Get type.
     */
    public function getType()
    {
        return $this->type ?: 'string';
    }

    /**
     * @param  string  $type  Type to set.
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
