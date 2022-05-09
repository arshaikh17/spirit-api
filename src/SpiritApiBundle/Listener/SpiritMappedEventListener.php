<?php

namespace Edcoms\SpiritApiBundle\Listener;

use Edcoms\SpiritApiBundle\Event\SpiritMappedEvent;
use Edcoms\SpiritApiBundle\Synchronizer\EntitySynchronizer;

/**
 * An event listener listening and to any SPIRIT call events.
 * When such an event is dispatched, the models contained within the event are sent to the Synchronizer service,
 * to iterate through and synchronize the relating entities.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
final class SpiritMappedEventListener
{
    /**
     * @var  EntitySynchronizer
     */
    private $synchronizer;

    /**
     * @param  EntitySynchronizer  $synchronizer  The SPIRIT entity synchronizing service.
     */
    public function __construct(EntitySynchronizer $synchronizer)
    {
        $this->synchronizer = $synchronizer;
    }

    /**
     * Listener for the SPIRIT mapping event.
     *
     * @param  SpiritMappedEvent  $event  The dispatched event
     */
    public function onSpiritApiMap(SpiritMappedEvent $event)
    {
        $models = $event->getModels();
        $synchronizer = $this->synchronizer;
        $models = $synchronizer->filterSynchronizableModels($models);

        if (!empty($models)) {
            $synchronizer->synchronizeModels($models);
        }
    }
}
