<?php

namespace Edcoms\SpiritApiBundle\Mapper\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Informs the response mapping functionality which details a response field properties to a child to a model.
 *
 * @Annotation
 * @Target("PROPERTY")
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class MappingToModel
{
    /**
     * @Annotation\Required()
     *
     * @var  string
     */
    public $class;

    /**
     * @return  string
     */
    public function getClass(): string
    {
        return '\\' . ltrim($this->class, '\\');
    }

    /**
     * @param  string  $class  Class to set.
     */
    public function setClass(string $class)
    {
        $this->class = $class;
    }
}
