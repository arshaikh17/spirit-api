<?php

namespace Edcoms\SpiritApiBundle\Synchronizer\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Informs the synchronizer service how to map a Model object instance against an entity object.
 *
 * @Annotation
 * @Target("CLASS")
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class SyncEntity
{
    /**
     * @var  bool
     */
    public $autoSync = false;

    /**
     * @Annotation\Required()
     *
     * @var  string
     */
    public $entity;

    /**
     * @Annotation\Required()
     *
     * @var  string
     */
    public $modelId;

    /**
     * @return  bool  Get auto sync.
     */
    public function getAutoSync(): bool
    {
        return $this->autoSync;
    }

    /**
     * @return  string  Get entity.
     */
    public function getEntity(): string
    {
        return '\\' . ltrim($this->entity, '\\');
    }

    /**
     * @return  string  Get model id property.
     */
    public function getmodelId(): string
    {
        return $this->modelId;
    }
}
