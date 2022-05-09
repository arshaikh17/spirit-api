<?php

namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Mapper\Annotation\MappingToModel;
use Edcoms\SpiritApiBundle\Model\AbstractModel;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncEntity;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncProperty;

/**
 * Object representation of organisation data retrieved from SPIRIT. 
 *
 * @SyncEntity(entity="Edcoms\SpiritApiBundle\Entity\SpiritOrganisation", modelId="id", autoSync=true)
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class Organisation extends AbstractModel
{
    /**
     * @Mapping(name="OrgId", type="string")
     * @SyncProperty(property="spiritId")
     */
    public $id;

    /**
     * @Mapping(name="Active", type="boolean")
     */
    public $isActive;

    /**
     * @Mapping(name="Name", otherNames="OrgName|OrganisationName", type="string")
     * @SyncProperty(property="name")
     */
    public $name;

    /**
     * @Mapping(name="Address1", otherNames="AddressLine1", type="string")
     * @SyncProperty(property="address1")
     */
    public $address1;

    /**
     * @Mapping(name="Address2", otherNames="AddressLine2", type="string")
     * @SyncProperty(property="address2")
     */
    public $address2;

    /**
     * @Mapping(name="Address3", otherNames="AddressLine3", type="string")
     * @SyncProperty(property="address3")
     */
    public $address3;

    /**
     * @Mapping(name="Town", type="string")
     * @SyncProperty(property="town")
     */
    public $town;

    /**
     * @Mapping(name="Region", type="string")
     * @SyncProperty(property="region")
     */
    public $region;

    /**
     * @Mapping(name="Postcode", type="string")
     * @SyncProperty(property="postcode")
     */
    public $postcode;

    /**
     * @MappingToModel("Edcoms\SpiritApiBundle\Model\Country")
     * @SyncProperty(
     *     property="country",
     *     subSyncProperty="name"
     * )
     */
    public $country;

    /**
     * @Mapping(name="EmailAddress", type="string")
     * @SyncProperty(property="email")
     */
    public $email;

    /**
     * @Mapping(name="AccountStatus", type="string")
     */
    public $status;

    /**
     * @var  \Edcoms\SpiritApiBundle\Model\OrganisationType
     *
     * @MappingToModel("Edcoms\SpiritApiBundle\Model\OrganisationType")
     * @SyncProperty(
     *     property="organisationType",
     *     subSyncProperty="id",
     *     normalizeEntity={"class"="\Edcoms\SpiritApiBundle\Entity\SpiritOrganisationType", "property"="spiritId"}
     * )
     */
    public $type;

    /**
     * @Mapping(name="UserDefinedFields", type="array")
     */
    public $userDefinedFields;

    /**
     * $latitude - there is no mapping for this as it gets assigned from the userDefinedFields properties - this can change when spirit api provide a well formed response for userDefinedFields as an associative array.
     * @Mapping(name="Latitude", type="string")
     * @SyncProperty(property="latitude")
     */
    public $latitude;  

    /**
     * $longitude - there is no mapping for this as it gets assigned from the userDefinedFields properties - this can change when spirit api provide a well formed response for userDefinedFields as an associative array.
     * @Mapping(name="Longitude", type="string")
     * @SyncProperty(property="longitude")
     */
    public $longitude;

    /**
     * $pupilsOnRoll - there is no mapping for this as it gets assigned from the userDefinedFields properties - this can change when spirit api provide a well formed response for userDefinedFields as an associative array.
     * @Mapping(name="PupilsOnRoll", type="string")
     * @SyncProperty(property="pupilsOnRoll")
     */
    public $pupilsOnRoll;

    /**
     * @Mapping(name="PPNumber", type="float")
     * @SyncProperty(property="ppNumber")
     */
    public $ppNumber;

    /**
     * @Mapping(name="PPPercentage", type="float")
     * @SyncProperty(property="ppPercentage")
     */
    public $ppPercentage;

    /**
     * @Mapping(name="LEA", type="string")
     * @SyncProperty(property="localAuthority")
     */
    public $localAuthority;

    /**
     * @Mapping(name="FSMNumber", type="float")
     * @SyncProperty(property="fsmNumber")
     */
    public $fsmNumber;

    /**
     * @Mapping(name="FSMPercentage", type="float")
     * @SyncProperty(property="fsmPercentage")
     */
    public $fsmPercentage;

    /**
     * @Mapping(name="Telephone", type="string")
     * @SyncProperty(property="telephone")
     */
    public $telephone;

    /**
     * setLatitude - this is really bad but until SPIRIT API provide an associative array - do not change these setters.
     * NB: DO NOT CHANGE THE ORDER OF userDefinedFields in the apiCall created in OrganisationHelper getById()
     */
    public function setLatitudeFromAttributes()
    {
        $this->latitude = null;

        if (array_key_exists('Latitude', $this->userDefinedFields)) {
            $this->latitude = $this->userDefinedFields['Latitude'];
        }
    }

    /**
     * setLongitude - this is really bad but until SPIRIT API provide an associative array - do not change these setters.
     * NB: DO NOT CHANGE THE ORDER OF userDefinedFields in the apiCall created in OrganisationHelper getById()
     */
    public function setLongitudeFromAttributes()
    {
        $this->longitude = null;

        if (array_key_exists('Longitude', $this->userDefinedFields)) {
            $this->longitude = $this->userDefinedFields['Longitude'];
        }
    }

    /**
     * setPupilsOnRoll - this is really bad but until SPIRIT API provide an associative array - do not change these setters.
     * NB: DO NOT CHANGE THE ORDER OF userDefinedFields in the apiCall created in OrganisationHelper getById()
     */
    public function setPupilsOnRollFromAttributes()
    {
        $this->pupilsOnRoll = null;

        if (array_key_exists('PupilsOnRoll', $this->userDefinedFields)) {
            $this->pupilsOnRoll = $this->userDefinedFields['PupilsOnRoll'];
        }
    }

}
