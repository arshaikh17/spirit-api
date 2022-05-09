<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2018-01-04 17:09:29
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-09 18:48:24
 */
namespace Edcoms\SpiritApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Edcoms\SpiritApiBundle\Entity\Interfaces\SpiritSynchronizableInterface;
use Edcoms\SpiritApiBundle\Entity\Interfaces\MetaDataSupportedObjectInterface;
use Edcoms\SpiritApiBundle\Entity\SpiritUser;
use Edcoms\SpiritApiBundle\Model\Organisation;

/**
 * Entity storing details of an existing SPIRIT Product Usage object.
 *
 * @author  Daniel Forer <daniel@forermedia.com>
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Edcoms\SpiritApiBundle\Entity\SpiritProductUsageRepository")
 */
class SpiritProductUsage implements SpiritSynchronizableInterface, MetaDataSupportedObjectInterface
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
     * @ORM\Column(name="last_synchronized", type="datetime", nullable=true)
     */
    protected $lastSynchronized;


    /**
     * @var  SpiritProduct
     *
     * @ORM\ManyToOne(targetEntity="Edcoms\SpiritApiBundle\Entity\SpiritProduct")
     * @ORM\JoinColumn(name="spirit_product_id", referencedColumnName="id", nullable=true)
     */
    protected $spiritProduct;

    /**
     * @var  SpiritOrganisation
     *
     * @ORM\ManyToOne(targetEntity="Edcoms\SpiritApiBundle\Entity\SpiritOrganisation")
     * @ORM\JoinColumn(name="spirit_organisation_id", referencedColumnName="id", nullable=true)
     */
    protected $spiritOrganisation;

    /**
     * @var  string
     *
     * @ORM\Column(name="spirit_person_id", type="string", length=50, nullable=true)
     */
    protected $spiritPersonId;

    /**
     * @var  SpiritWebAccount
     *
     * @ORM\ManyToOne(targetEntity="Edcoms\SpiritApiBundle\Entity\SpiritUser")
     * @ORM\JoinColumn(name="spirit_user_id", referencedColumnName="id", nullable=true)
     */
    protected $spiritUser;

    /**
     * @var  DateTime
     *
     * @ORM\Column(name="transactionDate", type="datetime", nullable=true)
     */
    protected $transactionDate;    

    /**
     * @var  decimal
     *
     * @ORM\Column(name="value", type="decimal", nullable=true)
     */
    protected $value;

    /**
     * @var  SpiritProductUsageTransactionType
     *
     * @ORM\ManyToOne(targetEntity="Edcoms\SpiritApiBundle\Entity\SpiritProductUsageTransactionType")
     * @ORM\JoinColumn(name="spirit_product_usage_transaction_type_id", referencedColumnName="id", nullable=true)
     */
    protected $spiritProductUsageTransactionType;

    /**
     * @var  string
     *
     * @ORM\Column(name="spirit_content_id", type="string", length=50, nullable=true)
     */
    protected $spiritContentId;

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
     * @return  string  Get SpiritProduct
     */
    public function getSpiritProduct ()
    {
        return $this->spiritProduct;
    }
    
    /**
     * @param   string|null  Set spiritProduct.
     *
     * @return  SpiritProductUsage
     */
    public function setSpiritProduct(SpiritProduct $spiritProduct = null): self
    {
        $this->spiritProduct = $spiritProduct;

        return $this;
    }

    /**
     * @return  string  Get SpiritOrganisation
     */
    public function getSpiritOrganisation ()
    {
        return $this->spiritOrganisation;
    }
    
    /**
     * @param   string|null  Set spiritUser.
     *
     * @return  SpiritProductUsage
     */
    public function setSpiritOrganisation(SpiritOrganisation $spiritOrganisation = null): self
    {
        $this->spiritOrganisation = $spiritOrganisation;

        return $this;
    } 

    /**
     * @return  string  Get spirit person id.
     */
    public function getSpiritPersonId()
    {
        return $this->spiritPersonId;
    }

    /**
     * @param   string  $spiritPersonId     Set spiritPersonId
     *
     * @return  SpiritSynchronizableInterface  Instance returned for method chaining.
     */
    public function setSpiritPersonId(string $spiritPersonId)
    {
        $this->spiritPersonId = $spiritPersonId;

        return $this;
    }    

    /**
     * @return  string  Get SpiritUser
     */
    public function getSpiritUser ()
    {
        return $this->spiritUser;
    }
    
    /**
     * @param   string|null  Set spiritUser.
     *
     * @return  SpiritProductUsage
     */
    public function setSpiritUser(SpiritUser $spiritUser = null): self
    {
        $this->spiritUser = $spiritUser;

        return $this;
    }    

    /**
     * @return  DateTime  Get transaction date.
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * @param   DateTime  $transactionDate     Set transactionDate.
     *
     * @return  SpiritSynchronizableInterface  Instance returned for method chaining.
     */
    public function setTransactionDate($transactionDate)
    {
        if (is_string($transactionDate )) {
            $transactionDate = \DateTime::createFromFormat('Y-m-d H:i:s', $transactionDate);
        }

        $this->transactionDate = $transactionDate;
        return $this;
    }

    /**
     * @return  int  Get value.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param   int  $value     Set value.
     *
     * @return  SpiritSynchronizableInterface  Instance returned for method chaining.
     */
    public function setValue(int $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return  string  Get SpiritProductUsageTransactionType
     */
    public function getSpiritProductUsageTransactionType ()
    {
        return $this->spiritProductUsageTransactionType;
    }
    
    /**
     * @param   string|null  Set spiritProductUsageTransactionType.
     *
     * @return  SpiritProductUsage
     */
    public function setSpiritProductUsageTransactionType(SpiritProductUsageTransactionType $spiritProductUsageTransactionType = null): self
    {
        $this->spiritProductUsageTransactionType = $spiritProductUsageTransactionType;

        return $this;
    } 

    /**
     * @return  string  Get spiritContentId.
     */
    public function getSpiritContentId()
    {
        return $this->spiritContentId;
    }

    /**
     * @param   string  $spiritContentId     Set spiritContentId.
     *
     * @return  SpiritSynchronizableInterface  Instance returned for method chaining.
     */
    public function setSpiritContentId(string $spiritContentId)
    {
        $this->spiritContentId = $spiritContentId;

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
    Methods supporting metadata calls to spirit for SpiritProductUsage (ProductUsage) below.
    */

    /**
     * Returns the relevant objectID used in spirit MetaData endpoint for SpiritProductUsage.
     * This will be the necessary data which will be used to save and load MetaData object in the SPIRIT service.
     *
     * @return  integer  objectID - a constant object type ID defined in spirit v8.
     */
    public function getObjectId()
    {
        return $this::OBJECTID_PRODUCTUSAGE; //defined in Edcoms\SpiritApiBundle\Entity\Interfaces\MetaDataSupportedObjectInterface.
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
