<?php

namespace Edcoms\SpiritApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Edcoms\SpiritApiBundle\Entity\Interfaces\SpiritSynchronizableInterface;
use Edcoms\SpiritApiBundle\Entity\Interfaces\MetaDataSupportedObjectInterface;
use Edcoms\SpiritApiBundle\Entity\SpiritUser;
use Edcoms\SpiritApiBundle\Model\Organisation;

/**
 * Entity storing details of an existing SPIRIT Orgnisation object.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Edcoms\SpiritApiBundle\Entity\SpiritOrganisationRepository")
 */
class SpiritOrganisation implements SpiritSynchronizableInterface, MetaDataSupportedObjectInterface
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    protected $name;

    /**
     * @var  string
     *
     * @ORM\Column(name="address_1", type="string", length=100, nullable=true)
     */
    protected $address1;

    /**
     * @var  string
     *
     * @ORM\Column(name="address_2", type="string", length=100, nullable=true)
     */
    protected $address2;

    /**
     * @var  string
     *
     * @ORM\Column(name="address_3", type="string", length=100, nullable=true)
     */
    protected $address3;

    /**
     * @var  string
     *
     * @ORM\Column(name="town", type="string", length=50, nullable=true)
     */
    protected $town;

    /**
     * @var  string
     *
     * @ORM\Column(name="region", type="string", length=50, nullable=true)
     */
    protected $region;

    /**
     * @var  string
     *
     * @ORM\Column(name="country", type="string", length=50, nullable=true)
     */
    protected $country;

    /**
     * @var  string
     *
     * @ORM\Column(name="postcode", type="string", length=50, nullable=true)
     */
    protected $postcode;

    /**
     * @var  string
     *
     * @ORM\Column(name="latitude", type="string", length=10, nullable=true)
     */
    protected $latitude;

    /**
     * @var  string
     *
     * @ORM\Column(name="longitude", type="string", length=10, nullable=true)
     */
    protected $longitude;

    /**
     * @var  string
     *
     * @ORM\Column(name="pupilsOnRoll", type="integer", nullable=true)
     */
    protected $pupilsOnRoll;

    /** @var  float
     *
     * @ORM\Column(name="ppnumber", type="float", nullable=true)
     */
    protected $PPNumber;

    /** @var  float
     *
     * @ORM\Column(name="pppercentage", type="float", nullable=true)
     */
    protected $PPPercentage;

    /** @var  string
     *
     * @ORM\Column(name="local_authority", type="string", nullable=true)
     */
    protected $localAuthority;

    /** @var  float
     *
     * @ORM\Column(name="fsmnumber", type="float", nullable=true)
     */
    protected $FSMNumber;

    /** @var  float
     *
     * @ORM\Column(name="fsmpercentage", type="float", nullable=true)
     */
    protected $FSMPercentage;

    /**
     * @var  string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @var  string
     *
     * @ORM\Column(name="telephone", type="string", length=50, nullable=true)
     */
    protected $telephone;

    /**
     * @var  ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Edcoms\SpiritApiBundle\Entity\SpiritUser", mappedBy="spiritOrganisation")
     */
    protected $spiritUsers;

    /**
     * @var  \Edcoms\SpiritApiBundle\Entity\SpiritOrganisationType
     *
     * @ORM\ManyToOne(targetEntity="Edcoms\SpiritApiBundle\Entity\SpiritOrganisationType")
     * @ORM\JoinColumn(name="spirit_organisation_type_id", referencedColumnName="id")
     */
    protected $organisationType;


    public function __construct()
    {
        $this->spiritUsers = new ArrayCollection();
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
     * @return  SpiritOrganisation
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
     * @param   SpiritUser  Add SPIRIT user.
     *
     * @return  SpiritOrganisation
     */
    public function addSpiritUser(SpiritUser $user)
    {
        if (!$this->spiritUsers->contains($user)) {
            $this->spiritUsers->add($user);

            $user->setSpiritOrganisation($this);
        }

        return $this;
    }

    /**
     * @return  ArrayCollection  Get SPIRIT users.
     */
    public function getSpiritUsers()
    {
        return $this->spiritUsers;
    }

    /**
     * @param   SpiritUser  Remove SPIRIT user.
     *
     * @return  SpiritOrganisation
     */
    public function removeSpiritUser(SpiritUser $user)
    {
        if ($this->spiritUsers->contains($user)) {
            $this->spiritUsers->remove($user);

            $user->setSpiritOrganisation(null);
        }

        return $this;
    }

    /**
     * @return  string  Get name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param   string  $name  Set name.
     *
     * @return  SpiritOrganisation
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return  string  Get address 1.
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param   string  $address1  Set address 1.
     *
     * @return  SpiritOrganisation
     */
    public function setAddress1(string $address1 = null): self
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * @return  string  Get address 2.
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param   string  $address2  Set address 2.
     *
     * @return  SpiritOrganisation
     */
    public function setAddress2(string $address2 = null): self
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * @return  string  Get address 3.
     */
    public function getAddress3()
    {
        return $this->address3;
    }

    /**
     * @param   string  $address3  Set address 3.
     *
     * @return  SpiritOrganisation
     */
    public function setAddress3(string $address3 = null): self
    {
        $this->address3 = $address3;

        return $this;
    }

    /**
     * @return  string  Get town.
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param   string  $town  Set town.
     *
     * @return  SpiritOrganisation
     */
    public function setTown(string $town = null): self
    {
        $this->town = $town;

        return $this;
    }

    /**
     * @return  string  Get region.
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param   string  $region  Set region.
     *
     * @return  SpiritOrganisation
     */
    public function setRegion(string $region = null): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country) {
        $this->country = $country;
    }

    /**
     * @return  string  Get postcode.
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param   string  $postcode  Set postcode.
     *
     * @return  SpiritOrganisation
     */
    public function setPostcode(string $postcode = null): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * @return  string  Get email.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param   string  $email  Set email.
     *
     * @return  SpiritOrganisation
     */
    public function setEmail(string $email = null): self
    {
        $this->email = $email;

        return $this;
    }


    /**
     * @return  string  Get latitude.
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param   string  $latitude  Set latitude.
     *
     * @return  SpiritOrganisation
     */
    public function setLatitude(string $latitude = null): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return  string  Get longitude.
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param   string  $longitude  Set longitude.
     *
     * @return  SpiritOrganisation
     */
    public function setLongitude(string $longitude = null): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return  string  Get pupilsOnRoll.
     */
    public function getPupilsOnRoll()
    {
        return $this->pupilsOnRoll;
    }

    /**
     * @param   string  $pupilsOnRoll  Set pupilsOnRoll.
     *
     * @return  SpiritOrganisation
     */
    public function setPupilsOnRoll(string $pupilsOnRoll = null): self
    {
        $this->pupilsOnRoll = $pupilsOnRoll;

        return $this;
    }

    /**
     * @return  string  Get telephone.
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param   string  $telephone  Set telephone.
     *
     * @return  SpiritOrganisation
     */
    public function setTelephone(string $telephone = null): self {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return \Edcoms\SpiritApiBundle\Entity\SpiritOrganisationType
     */
    public function getOrganisationType() {
        return $this->organisationType;
    }

    /**
     * @param \Edcoms\SpiritApiBundle\Entity\SpiritOrganisationType $organisationType
     *
     * @return  SpiritOrganisation
     */
    public function setOrganisationType(\Edcoms\SpiritApiBundle\Entity\SpiritOrganisationType $organisationType = null) {
        $this->organisationType = $organisationType;
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
    Methods supporting metadata calls to spirit for SpiritOrganisation (Organisation) below.
    */

    /**
     * Gets the static objectID (which defines the type of object i.e. Person = 2, WebAccount = 15).
     * This will be the necessary data which will be used to save and load MetaData object in the SPIRIT service.
     *
     * @return  integer  objectID - a constant object type ID defined in spirit v8.
     */
    public function getObjectId()
    {
        return $this::OBJECTID_ORGANISATION; //defined in Edcoms\SpiritApiBundle\Entity\Interfaces\MetaDataSupportedObjectInterface.
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

    /**
     * @return float
     */
    public function getPPNumber() {
        return $this->PPNumber;
    }

    /**
     * @param float $PPNumber
     */
    public function setPPNumber($PPNumber) {
        $this->PPNumber = $PPNumber;
    }

    /**
     * @return float
     */
    public function getPPPercentage() {
        return $this->PPPercentage;
    }

    /**
     * @param float $PPPercentage
     */
    public function setPPPercentage($PPPercentage) {
        $this->PPPercentage = $PPPercentage;
    }

    /**
     * @return string
     */
    public function getLocalAuthority() {
        return $this->localAuthority;
    }

    /**
     * @param string $localAuthority
     */
    public function setLocalAuthority($localAuthority) {
        $this->localAuthority = $localAuthority;
    }

    /**
     * @return float
     */
    public function getFSMNumber() {
        return $this->FSMNumber;
    }

    /**
     * @param float $FSMNumber
     */
    public function setFSMNumber($FSMNumber) {
        $this->FSMNumber = $FSMNumber;
    }

    /**
     * @return float
     */
    public function getFSMPercentage() {
        return $this->FSMPercentage;
    }

    /**
     * @param float $FSMPercentage
     */
    public function setFSMPercentage($FSMPercentage) {
        $this->FSMPercentage = $FSMPercentage;
    }
}
