<?php

namespace Edcoms\SpiritApiBundle\Call;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Model\WebAccount;

/**
 * An object containing the necessary data to make API endpoint calls to create/update a WebAccount.
 *
 * @see  http://apidocs.educationcompany.co.uk/#webaccounts-api-post
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class OrganisationCall extends ApiCall
{
    /**
     * Creates an ApiCall object, so that it can be used to call the SPIRIT service to create an Organisation.
     *
     * @param   SpiritOrganisation  $spiritOrganisation  The model object to grab the data from to send to SPIRIT.
     *
     * @return  self                     The created ApiCall object.
     */
    public static function create(SpiritOrganisation $spiritOrganisation): self
    {
        $data = $this->organisationPayload($spiritOrganisation);

        $apiCall = new self('POST', '/Organisations', $data);

        return $apiCall;
    }


    /**
     * Creates an ApiCall object, so that it can be used to call the SPIRIT service to update an Organisation.
     *
     * @param   SpiritOrganisation  $spiritOrganisation  The model object to grab the data from to send to SPIRIT.
     *
     * @return  self                     The updated ApiCall object.
     */
    public static function update(SpiritOrganisation $spiritOrganisation): self
    {
        $data = $this->organisationPayload($spiritOrganisation);

        $apiCall = new self('PUT', '/Organisations', $data);

        return $apiCall;
    }

    private function organisationPayload(SpiritOrganisation $spiritOrganisation) 
    { 

        $data = [
            'Name' => $spiritOrganisation->getName(),
            'OrganisationTypeId' => $spiritOrganisation->getType()->getId(),
            'AddressLine1' => $spiritOrganisation->getAddress1(),
            'AddressLine2' => $spiritOrganisation->getAddress2(),
            'AddressLine3' => $spiritOrganisation->getAddress3(),
            'AddressLine4' => '',
            'TownOrCity' => $spiritOrganisation->getTown(),
            'Region' => $spiritOrganisation->getRegion(),
            'Postcode' => $spiritOrganisation->getPostcode(),
            'CountryId' => $spiritOrganisation->getCountry()->getId(), //needs attention (do we have id from spirit for country)
            'Telephone' => $spiritOrganisation->getTelephone(),
            'EmailAddress' =>  ''
        ];

        return $data;
    }

}
