<?php

namespace Edcoms\SpiritApiBundle\Helper;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Call\OrganisationCall;
use Edcoms\SpiritApiBundle\Caller\ApiCaller;
use Edcoms\SpiritApiBundle\Helper\AbstractHelper;
use Edcoms\SpiritApiBundle\Model\Organisation;
use Edcoms\SpiritApiBundle\Response\ApiResponse;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;

/**
 * Service used to make API calls.
 * This particular service is dedicated to make calls relating to Organisations.
 *
 * @see  http://apidocs.educationcompany.co.uk/#organisations
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class OrganisationHelper extends AbstractHelper
{

    private $userDefinedFields = null;

    /**
     * Fetches organisation by id as search criteria.
     * Ref: http://apidocs.educationcompany.co.uk/#organisation-api-get
     *
     * @param   string  $organisationId             Spirit ID of organisation used as search criteria
     * @param   bool    $includeUserDefinedFields   Response to include userDefinedFields
     *
     * @return  Organisation[]     Collection of found organisations.
     */
    public function getById(string $organisationId, bool $includeUserDefinedFields = false)
    {

        $apiCallData = [];

        if ($includeUserDefinedFields && $this->userDefinedFields != null) {
            $apiCallData = [
                'userDefinedFields' => $this->userDefinedFields
            ];
        } 

        $apiCall = new ApiCall(
            'GET',
            "/Organisations/{$organisationId}",
            $apiCallData
        );

        return $this->makeCallAndMapResponse($apiCall);
    }

    /**
     * Fetches organisations by using '$postcode' as search criteria.
     * Ref: http://apidocs.educationcompany.co.uk/#schoolsearch-api-get
     *
     * @param   string  $postcode   Postcode used as search criteria
     * @param   int     $searchType Restricts the returned results by group type (default = 1) is schools.
     * @param   int     $maxRecords     Max number of records to return in response (useful for paging). Default max is 2000.
     * @param   int     $skipRecords    Number of skipped records in return response (useful for paging). If none, 0-2000 return. If 2000, then 2000-4000 returned and so on.
     *
     * @return  Organisation[]     Collection of found organisations.
     */
    public function searchByPostcode(string $postcode, int $searchType = 1, int $maxRecords = null, int $skipRecords = null)
    {
        $apiCallData = [
            'postCode' => $postcode,
            'searchType' => $searchType
        ];

        if ($maxRecords !== null) {
            $apiCallData['maxRecords'] = $maxRecords;
        }

        if ($skipRecords !== null) {
            $apiCallData['skipRecords'] = $skipRecords;
        }         

        // if there is a search limit, set it in the API call.
        $searchLimit = $this->getSearchLimit();

        if ($searchLimit > 0) {
            $apiCallData['maxRecordsToReturn'] = $searchLimit;
        }

        $apiCall = new ApiCall('GET', '/Organisations/SchoolSearch', $apiCallData);

        return $this->makeCallAndMapResponse($apiCall);
    }

    /**
     * Calls the SPIRIT service to create an Organisation.
     * Ref: http://apidocs.educationcompany.co.uk/#organisation-create
     *
     * @param   SpiritOrganisation  $spiritOrganisation    The SpiritOrganisation to create.
     *
     * @return  SpiritOrganisation|BadApiResponse  '$spiritOrganisation' populated with the SpiritOrganisation ID, or the response.
     */
    public function createOrganisation(SpiritOrganisation $spiritOrganisation)
    {
        $spiritOrganisation = $this->normalizeModel($spiritOrganisation);
        $apiCall = OrganisationCall::Create($spiritOrganisation);

        $response = $this->makeCall($apiCall);

        // continues if an exception hasn't already been thrown.
        if ($response instanceof BadApiResponse) {
            return $response;
        }

        //required to set the returned spiritId against the model's id property.
        $spiritOrganisation->setId($response->getData());

        return $spiritOrganisation;
    }    

    /**
     * Calls the SPIRIT service to update an Organisation.
     * Ref: http://apidocs.educationcompany.co.uk/#organisation-update
     *
     * @param   SpiritOrganisation  $spiritOrganisation    The SpiritOrganisation to update.
     *
     * @return  SpiritOrganisation|BadApiResponse  '$spiritOrganisation' populated with the updated SpiritOrganisation, or the response.
     */
    public function updateOrganisation(SpiritOrganisation $spiritOrganisation)
    {
        $spiritOrganisation = $this->normalizeModel($spiritOrganisation);
        $apiCall = OrganisationCall::Create($spiritOrganisation);

        $response = $this->makeCall($apiCall);

        // continues if an exception hasn't already been thrown.
        if ($response instanceof BadApiResponse) {
            return $response;
        }

        return $spiritOrganisation;
    } 


    /**
     * {inheritdoc}
     */
    public function classToMap(): string
    {
        return Organisation::class;
    }

    /**
     * @param $fields string
     */
    public function setUserDefinedFields($fields){
        $this->userDefinedFields = $fields;
    }

    /**
     * @return  int  Get search limit.
     */
    private function getSearchLimit(): int
    {
        return intval($this->getOption('search_limit'));
    }
}
