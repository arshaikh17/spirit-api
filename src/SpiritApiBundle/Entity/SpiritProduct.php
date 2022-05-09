<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2018-01-04 17:22:55
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:23:09
 */
namespace Edcoms\SpiritApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Edcoms\SpiritApiBundle\Entity\Interfaces\MetaDataSupportedObjectInterface;
use Edcoms\SpiritApiBundle\Entity\Interfaces\SpiritSynchronizableInterface;

/**
 * Entity storing details of a Spirit Product object.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Edcoms\SpiritApiBundle\Entity\SpiritProductRepository")
 */
class SpiritProduct implements SpiritSynchronizableInterface, MetaDataSupportedObjectInterface
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var  string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    protected $code;

    /**
     * @var  string
     * 
     * @ORM\Column(name="displayName", type="string", length=255, nullable=true)
     */
    protected $displayName;

    /**
     * @var  string
     *
     * @ORM\Column(name="lookupCode", type="string", length=255, nullable=true)
     */
    protected $lookupCode;

    /**
     * @var  string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * @var  boolean
     * 
     * @ORM\Column(name="showOnWebSite", type="boolean", nullable=true)
     */
    protected $showOnWebSite = false;

    /**
     * @var  boolean
     * 
     * @ORM\Column(name="digital", type="boolean", nullable=true)
     */
    protected $digital = false;

    /**
     * @var  boolean
     * 
     * @ORM\Column(name="containsDigitalAssets", type="boolean", nullable=true)
     */
    protected $containsDigitalAssets = false;

    /**
     * @var  boolean
     * 
     * @ORM\Column(name="stock", type="boolean", nullable=true)
     */
    protected $stock = true;

    /**
     * @var  boolean
     * 
     * @ORM\Column(name="acceptBackOrders", type="boolean", nullable=true)
     */
    protected $acceptBackOrders = false;

    /**
     * @var  datetime
     * 
     * @ORM\Column(name="publicationDate", type="date", nullable=true)
     */
    protected $publicationDate;

    /**
     * @var  datetime
     * 
     * @ORM\Column(name="reprintDate", type="date", nullable=true)
     */
    protected $reprintDate;

    /**
     * @var  boolean
     * 
     * @ORM\Column(name="subscription", type="boolean", nullable=true)
     */
    protected $subscription = false;

    /**
     * @var  int
     * 
     * @ORM\Column(name="subscriptionLengthDays", type="integer", nullable=true)
     */
    protected $subscriptionLengthDays = 0;

    /**
     * @var  boolean
     * 
     * @ORM\Column(name="pipeline", type="boolean", nullable=true)
     */
    protected $pipeline = false;

    /**
     * @var  array
     *
     * @ORM\Column(name="attributes", type="array", nullable=true) 
     */
    protected $attributes;

    public function __construct()
    {
        $this->attributes = array();
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
     * @return  string  Get Name
     */
    public function getName ()
    {
        return $this->name;
    }
    
    /**
     * @param   string|null  Set name.
     *
     * @return  SpiritProduct
     */
    public function setName(string $name = null): self
    {
        $this->name = $name;

        return $this;
    }    
    
    /**
     * @return  string  Get code.
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param   string|null  Set code.
     *
     * @return  SpiritProduct
     */
    public function setCode(string $code = null): self
    {
        $this->code = $code;

        return $this;
    }
    
    /**
     * @return  string  Get Display Name.
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param   string|null  Set Display Name.
     *
     * @return  SpiritProduct
     */
    public function setDisplayName(string $displayName = null): self
    {
        $this->displayName = $displayName;

        return $this;
    }
    
    /**
     * @return  string  Get LookupCode
     */
    public function getLookupCode()
    {
        return $this->lookupCode;
    }

    /**
     * @param   string|null  Set Lookup Code.
     *
     * @return  SpiritProduct
     */
    public function setLookupCode(string $lookupCode = null): self
    {
        $this->lookupCode = $lookupCode;

        return $this;
    }

    /**
     * @return  string  Get Description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param   string|null  Set Description.
     *
     * @return  SpiritProduct
     */
    public function setDescription(string $description = null): self
    {
        $this->description = $description;

        return $this;
    }
    
    /**
     * @return  boolean  Get ShowOnWebSite
     */
    public function getShowOnWebSite()
    {
        return $this->showOnWebSite;
    }

    /**
     * @param   string|null  Set ShowOnWebSite.
     *
     * @return  SpiritProduct
     */
    public function setShowOnWebSite(bool $showOnWebSite = null): self
    {
        $this->showOnWebSite = $showOnWebSite;

        return $this;
    }
    
    /**
     * @return  boolean  Get Digital
     */
    public function getDigital()
    {
        return $this->digital;
    }

    /**
     * @param   boolean|null  Set digital.
     *
     * @return  SpiritProduct
     */
    public function setDigital(bool $digital = null): self
    {
        $this->digital = $digital;

        return $this;
    }
    
    /**
     * @return  boolean  Get ContainsDigitalAssets
     */
    public function getContainsDigitalAssets()
    {
        return $this->containsDigitalAssets;
    }

    /**
     * @param   boolean|null  Set containsDigitalAssets
     *
     * @return  SpiritProduct
     */
    public function setContainsDigitalAssets(bool $containsDigitalAssets = null): self
    {
        $this->containsDigitalAssets = $containsDigitalAssets;

        return $this;
    }
    
    /**
     * @return  boolean  Get Stock
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param   boolean|null  Set Stock.
     *
     * @return  SpiritProduct
     */
    public function setStock(bool $stock = null): self
    {
        $this->stock = $stock;

        return $this;
    }
    
    /**
     * @return  boolean  Get AcceptBackOrders
     */
    public function getAcceptBackOrders()
    {
        return $this->acceptBackOrders;
    }

    /**
     * @param   boolean|null  Set acceptBackOrders.
     *
     * @return  SpiritProduct
     */
    public function setAcceptBackOrders(bool $acceptBackOrders = null): self
    {
        $this->acceptBackOrders = $acceptBackOrders;

        return $this;
    }
    
    /**
     * @return  datetime  Get PublicationDate
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @param   datetime|null  Set publicationDate.
     *
     * @return  SpiritProduct
     */
    public function setPublicationDate($publicationDate = null): self
    {
        if (is_string($publicationDate )) {
            $publicationDate = \DateTime::createFromFormat('Y-m-d H:i:s', $publicationDate);
        }        

        $this->publicationDate = $publicationDate;

        return $this;
    }
    
    /**
     * @return  datetime  Get ReprintDate
     */
    public function getReprintDate()
    {
        return $this->reprintDate;
    }

    /**
     * @param   datetime|null  Set reprintDate
     *
     * @return  SpiritProduct
     */
    public function setReprintDate($reprintDate = null): self
    {
        if (is_string($reprintDate )) {
            $reprintDate = \DateTime::createFromFormat('Y-m-d H:i:s', $reprintDate);
        }

        $this->reprintDate = $reprintDate;

        return $this;
    }
    
    /**
     * @return  boolean  Get Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @param   boolean|null  Set subscription
     *
     * @return  SpiritProduct
     */
    public function setSubscription(bool $subscription = null): self
    {
        $this->subscription = $subscription;

        return $this;
    }
    
    /**
     * @return  int  Get SubscriptionLengthDays
     */
    public function getSubscriptionLengthDays()
    {
        return $this->subscriptionLengthDays;
    }

    /**
     * @param   int|null  Set SPIRIT id.
     *
     * @return  SpiritProduct
     */
    public function setSubscriptionLengthDays(int $subscriptionLengthDays = null): self
    {
        $this->subscriptionLengthDays = $subscriptionLengthDays;

        return $this;
    }
    
    /**
     * @return  boolean  Get Pipeline
     */
    public function getPipeline()
    {
        return $this->pipeline;
    }

    /**
     * @param   boolean|null  Set pipeline
     *
     * @return  SpiritProduct
     */
    public function setPipeline(bool $pipeline = null): self
    {
        $this->pipeline = $pipeline;

        return $this;
    }
    
    /**
     * @return  array  Get Attributes
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param   array|null  Set attributes.
     *
     * @return  SpiritProduct
     */
    public function setAttributes($attributes = null): self
    {
        if (is_array($attributes)) {
            $this->attributes = $attributes;
        }

        return $this;
    }


    /**
     * {inheritdoc}
     */
    public function sendToSpirit(): bool
    {
        return null === $this->spiritId;
    }

    /**
     * Returns the relevant objectID used in spirit MetaData endpoint for SpiritProductUsage.
     * This will be the necessary data which will be used to save and load MetaData object in the SPIRIT service.
     *
     * @return  integer  objectID - a constant object type ID defined in spirit v8.
     */
    public function getObjectId()
    {
        return $this::OBJECTID_PRODUCT;
    }

    /**
     * Gets the object's unique spirit Primary Key (i.e. spiritId = 498d895c-0a57-4c95-83cd-f41d3c07d6e6).
     * This will be the necessary data which will be used to save and load MetaData object in the SPIRIT service.
     *
     * @return  string  spiritId for SpiritProductUsage.
     */
    public function getObjectPrimaryKey()
    {
        return $this->getSpiritId();
    }

}
