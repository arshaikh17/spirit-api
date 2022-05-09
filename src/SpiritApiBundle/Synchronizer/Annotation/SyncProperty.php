<?php

namespace Edcoms\SpiritApiBundle\Synchronizer\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Informs the synchronizer which properties to map against those in the synchronizing entities.
 *
 * @Annotation
 * @Target({"ANNOTATION", "PROPERTY"})
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class SyncProperty
{
    /**
     * @var  array
     */
    public $normalizeEntity = null;

    /**
     * @Annotation\Required()
     *
     * @var  string
     */
    public $property;

    /**
     * @var  string
     */
    public $subSyncProperty = null;

    /**
     * @return  array  Get normalize entity.
     */
    public function getNormalizeEntity()
    {
        return $this->normalizeEntity;
    }

    /**
     * @param  array|null  $normalizeEntity  Set normalize entity.
     */
    public function setNormalizeEntity(array $normalizeEntity = null)
    {
        $this->normalizeEntity = $normalizeEntity;
    }

    /**
     * @return  string  Get property.
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @param  string  $property  Property to set.
     */
    public function setProperty(string $property)
    {
        $this->property = $property;
    }

    /**
     * @return  string|null  Get sub sync property.
     */
    public function getSubSyncProperty()
    {
        return $this->subSyncProperty;
    }

    /**
     * @param  string|null  $subSyncProperty  Set sub sync property.
     */
    public function setSubSyncProperty(string $subSyncProperty = null)
    {
        $this->subSyncProperty = $subSyncProperty;
    }
}
