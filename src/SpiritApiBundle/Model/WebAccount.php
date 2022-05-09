<?php

namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Mapper\Annotation\MappingToModel;
use Edcoms\SpiritApiBundle\Model\AbstractModel;
use Edcoms\SpiritApiBundle\Model\Person;
use Edcoms\SpiritApiBundle\Model\WebAccountType;
use Edcoms\SpiritApiBundle\Model\WebUserType;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncEntity;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncMultipleProperty;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncProperty;

/**
 * Object representation of web account data retrieved from SPIRIT.
 *
 * @SyncEntity(entity="Edcoms\SpiritApiBundle\Entity\SpiritUser", modelId="id", autoSync=true)
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class WebAccount extends AbstractModel
{
    /**
     * @Mapping(name="WebAccountId", type="int")
     * @SyncProperty(property="spiritId")
     */
    public $id;

    /**
     * @Mapping(name="UserName", type="string")
     */
    public $username;

    /**
     * @Mapping(name="EmailAddress", type="string")
     */
    public $email;

    /**
     * @Mapping(name="Password", type="string")
     */
    public $password;

    /**
     * @Mapping(name="ScreenName", type="string")
     */
    public $screenName;

    /**
     * @var  Organisation
     *
     * @MappingToModel("Edcoms\SpiritApiBundle\Model\Organisation")
     * @SyncProperty(
     *     property="spiritOrganisation",
     *     subSyncProperty="id",
     *     normalizeEntity={"class"="\Edcoms\SpiritApiBundle\Entity\SpiritOrganisation", "property"="spiritId"}
     * )
     */
    public $organisation;

    /**
     * @MappingToModel("Edcoms\SpiritApiBundle\Model\Person")
     * @SyncProperty(property="spiritPersonId", subSyncProperty="id")
     */
    public $person;

    /**
     * @MappingToModel("Edcoms\SpiritApiBundle\Model\WebAccountType")
     */
    public $type;

    /**
     * @MappingToModel("Edcoms\SpiritApiBundle\Model\WebUserType")
     */
    public $userType;

    public function __construct()
    {
        $this->type = new WebAccountType();
        $this->userType = new WebUserType();
    }    
}
