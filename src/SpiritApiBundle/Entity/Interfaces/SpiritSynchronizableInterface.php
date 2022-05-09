<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2018-01-04 17:07:43
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:23:23
 */
namespace Edcoms\SpiritApiBundle\Entity\Interfaces;

/**
 * Interface which should only be implemented by SPIRIT entity objects.
 * This allows the synchronizer service to fetch the mnecessary data from SPIRIT,
 * and update the stored entity with the same ID.
 */
interface SpiritSynchronizableInterface
{
    /**
     * @return  DateTime|null  Get last synchronized.
     */
    public function getLastSynchronized(): \DateTime;

    /**
     * @param  DateTime  $lastSynchonized  Set last synchronized.
     */
    public function setLastSynchronized(\DateTime $lastSynchonized);

    /**
     * @return  bool  'true' indicating that the relating entity has been saved to the SPIRIT service.
     */
    public function sendToSpirit(): bool;
}
