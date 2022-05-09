<?php

namespace Edcoms\SpiritApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Edcoms\SpiritApiBundle\Entity\Interfaces\SpiritSynchronizableInterface;
use Edcoms\SpiritApiBundle\Entity\SpiritUser;
use Edcoms\SpiritApiBundle\Model\Organisation;

/**
 * Entity storing details of an existing Spirit OrganisationType Type object.
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class SpiritOrganisationType implements SpiritSynchronizableInterface
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;


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
     * @return  SpiritOrganisationType
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
     * @return  SpiritOrganisationType  Instance returned for method chaining.
     */
    public function setLastSynchronized(\DateTime $lastSynchronized)
    {
        $this->lastSynchronized = $lastSynchronized;

        return $this;
    }

    /**
     * @return  string
     */
    public function getName ()
    {
        return $this->name;
    }
    
    /**
     * @param   string|null
     *
     * @return  SpiritOrganisationType
     */
    public function setName(string $name = null): self
    {
        $this->name = $name;

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
