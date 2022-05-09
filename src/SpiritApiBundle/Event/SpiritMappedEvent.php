<?php

namespace Edcoms\SpiritApiBundle\Event;

use Edcoms\SpiritApiBundle\Model\AbstractModel;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event to be dispatched at the time of mapping a model object from a SPIRIT service call.
 *
 * @author  James Stubbs  <james.stubbs@edcoms.co.uk>
 */
class SpiritMappedEvent extends Event
{
    /**
     * @var  AbstractModel[]
     */
    private $models = [];

    /**
     * @param  mixed  $modelOrModels  Resulting mapped model(s) from the API call.
     */
    public function __construct($modelOrModels = null)
    {
        if (is_array($modelOrModels)) {
            $this->addModels($modelOrModels);
        } elseif ($modelOrModels !== null) {
            $this->addModel($modelOrModels);
        }
    }

    public function getModels(): array
    {
        return $this->models;
    }

    public function addModel(AbstractModel $model): self
    {
        $this->models[] = $model;

        return $this;
    }

    public function addModels(array $models): self
    {
        $this->models = array_merge($this->models, $models);

        return $this;
    }
}
