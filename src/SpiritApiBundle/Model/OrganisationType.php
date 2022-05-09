<?php

namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Model\AbstractModel;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncProperty;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncEntity;

/**
 * Object representation of organisation type type data retrieved from SPIRIT. 
 *
 * @SyncEntity(entity="Edcoms\SpiritApiBundle\Entity\SpiritOrganisationType", modelId="id", autoSync=true)
 */
class OrganisationType extends AbstractModel
{
    /**
     * @Mapping(name="OrganisationTypeId", otherNames="OrgTypeId|OrganisationTypeId", type="int")
     * @SyncProperty(property="spiritId")
     */
    public $id;

    /**
     * @Mapping(name="OrganisationTypeName", otherNames="OrgTypeName|OrganisationTypeName|OrgType", type="string")
     * @SyncProperty(property="name")
     */
    public $name;
}
