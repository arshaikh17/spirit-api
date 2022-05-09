<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2018-01-04 17:09:48
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:10:22
 */
namespace Edcoms\SpiritApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Edcoms\SpiritApiBundle\Entity\Interfaces\SpiritSynchronizableInterface;
use Edcoms\SpiritApiBundle\Entity\SpiritUser;
use Edcoms\SpiritApiBundle\Model\Organisation;

/**
 * Entity storing details of an existing SPIRIT Product Usage Transaction Type object.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Edcoms\SpiritApiBundle\Entity\SpiritProductUsageTransactionTypeRepository")
 */
class SpiritProductUsageTransactionType implements SpiritSynchronizableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var  string
     *
     * @ORM\Column(name="spirit_id", type="string", length=50, nullable=true)
     */
    protected $spiritId;

    /**
     * @var  DateTime
     *
     * @ORM\Column(name="last_synchronized", type="datetime")
     */
    protected $lastSynchronized;

    /**
     * @var  string
     * 
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * @var  boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    protected $active;

    public function __construct()
    {
        
    }

    /**
     * @return  int  Get id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return  string  Get SPIRIT id.
     */
    public function getSpiritId()
    {
        return $this->spiritId;
    }

    /**
     * @param   string|null  Set SPIRIT id.
     *
     * @return  SpiritProduct
     */
    public function setSpiritId(string $spiritId = null): self
    {
        $this->spiritId = $spiritId;

        return $this;
    }

    /**
     * @return  DateTime  Get last synchronized.
     */
    public function getLastSynchronized(): \DateTime
    {
        return $this->lastSynchronized;
    }

    /**
     * @param   DateTime  $lastSynchonized     Set last synchronized.
     *
     * @return  SpiritSynchronizableInterface  Instance returned for method chaining.
     */
    public function setLastSynchronized(\DateTime $lastSynchronized)
    {
        $this->lastSynchronized = $lastSynchronized;

        return $this;
    }

    /**
     * @return  string  Get Description
     */
    public function getDescription ()
    {
        return $this->description;
    }
    
    /**
     * @param   string|null  Set description.
     *
     * @return  SpiritProductUsageTransactionType
     */
    public function setDescription(string $description = null): self
    {
        $this->description = $description;

        return $this;
    }    
    
    /**
     * @return  boolean  Get active.
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param   boolean|null  Set active.
     *
     * @return  SpiritProductUsageTransactionType
     */
    public function setActive(bool $active = null): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * {inheritdoc}
     */
    public function sendToSpirit(): bool
    {
        return null === $this->spiritId;
    }

}
