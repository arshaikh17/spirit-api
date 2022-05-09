<?php

namespace Edcoms\SpiritApiBundle\Synchronizer\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 *
 *
 * @Annotation
 * @Target("PROPERTY")
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class SyncMultipleProperty
{
    /**
     * @Annotation\Required()
     *
     * @var  array
     */
    public $properties;

    /**
     * @return  array  Get properties.
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param  array  $properties  Set properties.
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }
}
