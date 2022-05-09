<?php

namespace Edcoms\SpiritApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Edcoms\SpiritApiBundle\Entity\Interfaces\SpiritSynchronizableInterface;
use Edcoms\SpiritApiBundle\Entity\Interfaces\MetaDataSupportedObjectInterface;
use Edcoms\SpiritApiBundle\Entity\Interfaces\TagSupportedObjectInterface;

/**
 * Entity storing details of an existing SPIRIT WebAccount object.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Edcoms\SpiritApiBundle\Entity\SpiritUserRepository")
 */
class SpiritUser implements SpiritSynchronizableInterface, MetaDataSupportedObjectInterface, TagSupportedObjectInterface
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
     * @var  string
     *
     * @ORM\Column(name="spirit_person_id", type="string", length=50, nullable=true)
     */
    protected $spiritPersonId;

    /**
     * @var  SpiritOrganisation
     *
     * @ORM\ManyToOne(targetEntity="Edcoms\SpiritApiBundle\Entity\SpiritOrganisation", inversedBy="spiritUsers", cascade={"persist"})
     * @ORM\JoinColumn(name="spirit_organisation_id", referencedColumnName="id", nullable=true)
     */
    protected $spiritOrganisation;

    /**
     * @var  bool
     *
     * @ORM\Column(name="last_synchronized", type="datetime", nullable=true, options={"default"=null})
     */
    protected $lastSynchronized;

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
    public function getSpiritId(): string
    {
        return $this->spiritId;
    }

    /**
     * @param   string      Set SPIRIT id.
     *
     * @return  SpiritUser  Instance returned for method chaining.
     */
    public function setSpiritId(string $spiritId)
    {
        $this->spiritId = $spiritId;

        return $this;
    }

    /**
     * @return  string  Get SPIRIT person id.
     */
    public function getSpiritPersonId()
    {
        return $this->spiritPersonId;
    }

    /**
     * @param   string|null  $spiritPersonId  Set SPIRIT person id.
     *
     * @return  SpiritUser
     */
    public function setSpiritPersonId(string $spiritPersonId = null)
    {
        $this->spiritPersonId = $spiritPersonId;

        return $this;
    }

    public function getSpiritOrganisation()
    {
        return $this->spiritOrganisation;
    }

    public function setSpiritOrganisation(SpiritOrganisation $spiritOrganisation = null)
    {
        $this->spiritOrganisation = $spiritOrganisation;

        return $this;
    }

    /**
     * @return  DateTime|null  Get last synchronized.
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
     * {inheritdoc}
     */
    public function sendToSpirit(): bool
    {
        return null === $this->spiritId;
    }

    /*
    Methods supporting metadata calls to spirit for SpiritUser (WebAccount) below.
    */

    /**
     * Gets the static objectID (which defines the type of object i.e. Person = 2, WebAccount = 15).
     * This will be the necessary data which will be used to save and load MetaData object in the SPIRIT service.
     *
     * @return  integer  objectID - a constant object type ID defined in spirit v8.
     */
    public function getObjectId()
    {
        return $this::OBJECTID_WEB_ACCOUNT; //defined in Edcoms\SpiritApiBundle\Entity\Interfaces\MetaDataSupportedObjectInterface.
    }

    /**
     * Gets a specific object's unique spirit Primary Key (i.e. spiritId = 498d895c-0a57-4c95-83cd-f41d3c07d6e6).
     * This will be the necessary data which will be used to save and load MetaData object in the SPIRIT service.
     *
     * @return  string  spiritId for SpiritUser (WebAccount).
     */
    public function getObjectPrimaryKey() 
    {
        return $this->getSpiritId();
    }

    public function getTagsEndpointURL(){
        return sprintf('/WebAccounts/%s/Tags', $this->getObjectPrimaryKey());
    }
}
