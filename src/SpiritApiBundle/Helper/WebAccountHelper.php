<?php

namespace Edcoms\SpiritApiBundle\Helper;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Call\WebAccountCall;
use Edcoms\SpiritApiBundle\Caller\ApiCaller;
use Edcoms\SpiritApiBundle\Helper\AbstractHelper;
use Edcoms\SpiritApiBundle\Model\WebAccount;
use Edcoms\SpiritApiBundle\Response\ApiResponse;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;

/**
 * Service used to make API calls.
 * This particular service is dedicated to make calls relating to WebAccounts.
 *
 * @see  http://apidocs.educationcompany.co.uk/#webaccounts
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class WebAccountHelper extends AbstractHelper
{
    /**
     * Calls the SPIRIT service to create a WebAccount.
     * Ref: http://apidocs.educationcompany.co.uk/#webaccounts-api-post
     *
     * @param   WebAccount  $webAccount    The WebAccount to create.
     *
     * @return  WebAccount|BadApiResponse  '$webAccount' populated with the WebAccount ID, or the response.
     */
    public function createAccount(WebAccount $webAccount)
    {
        $webAccount = $this->normalizeModel($webAccount);
        $apiCall = WebAccountCall::Create($webAccount);

        $response = $this->makeCall($apiCall);

        // continues if an exception hasn't already been thrown.
        if ($response instanceof BadApiResponse) {
            return $response;
        }

        //required to set the returned spiritId against the webaccount model's id property.
        $webAccount->setId($response->getData());

        //get entire newly created webaccount model from spirit, so data is all in-sync.
        $webAccount = $this->getById($webAccount->getId());

        return $webAccount;
    }

    /**
     * Calls the SPIRIT service to update a WebAccount.
     * Ref: http://apidocs.educationcompany.co.uk/#webaccounts-update
     *
     * @param   WebAccount  $webAccount    The WebAccount to update.
     *
     * @return  WebAccount|BadApiResponse  '$webAccount' populated with the WebAccount ID, or the response.
     */
    public function updateAccount(WebAccount $webAccount, $updateOrganisation = false)
    {
        $webAccount = $this->normalizeModel($webAccount);
        $apiCall = WebAccountCall::Update($webAccount, $updateOrganisation);

        $response = $this->makeCall($apiCall);    

        // continues if an exception hasn't already been thrown.
        if ($response instanceof BadApiResponse) {
            return $response;
        }

        return $webAccount;
    }

    /**
     * Calls the SPIRIT service to update a WebAccount.
     * Ref: http://apidocs.educationcompany.co.uk/#webaccounts-update
     *
     * @param   WebAccount  $webAccount    The WebAccount to update.
     *
     * @return  true|BadApiResponse  'true' or the response.
     */
    public function updatePassword($webAccountId, $newPassword)
    {
        $apiCall = WebAccountCall::UpdatePassword($webAccountId, $newPassword);

        $response = $this->makeCall($apiCall);

        // continues if an exception hasn't already been thrown.
        if ($response instanceof BadApiResponse) {
            return $response;
        }

        return $response->getData();
    }

    /**
     * Calls the SPIRIT service to authenticate a WebAccount.
     * Ref: http://apidocs.educationcompany.co.uk/#authenticate-web-account
     *
     * @param   WebAccount  $webAccount    The WebAccount to authenticate.
     *
     * @return  WebAccount|BadApiResponse  '$webAccount' populated with the WebAccount ID, or the response.
     */
    public function authenticateAccount($username, $password, $webAccountUserTypeId)
    {
      $apiCall = WebAccountCall::Authenticate($username, $password, $webAccountUserTypeId);

      $response = $this->makeCall($apiCall);

      // continues if an exception hasn't already been thrown.
      if ($response instanceof BadApiResponse) {
        return $response;
      }

      return $this->mapper->mapFromResponse($response, $this->classToMap());

    }

    /**
     * Calls the SPIRIT service to retrieve a WebAccount object by the ID.
     * Ref: http://apidocs.educationcompany.co.uk/#webaccounts-api-get
     *
     * @param   string $webAccountId  SPIRIT ID of the WebAccount object to retrieve.
     *
     * @return  WebAccount|null       The returned WebAccount from the SPIRIT service, or null if not found.
     */
    public function getById(string $webAccountId, bool $includeUserDefinedFields = false)
    {

        //see OrganisationHelper for way to include userDefinedFields in apiCall.

        $apiCall = new ApiCall(
            'GET',
            "/WebAccounts/{$webAccountId}"
        );

        return $this->makeCallAndMapResponse($apiCall);
    }

    /**
     * Request to be forgotten https://spiritcrm.co.uk/Service855/docs/#operation/Persons_RtbfRequest
     * @param string $webAccountId
     * @param bool $skipApproval
     *
     * @return bool
     */
    public function requestToBeForgotten(string $webAccountId, $skipApproval = false){
        $webAccount = $this->getById($webAccountId);

        $result = false;
        if($webAccount){
            $queryData = [];
            if($skipApproval == true){
                $queryData['noApprovalRequired'] = 'TRUE';
            }
            $apiCall = new ApiCall('POST', sprintf('/Persons/%s/RtbfRequest', $webAccount->person->id), [], $queryData);
            $response = $this->makeCall($apiCall);
            /** @var \Edcoms\SpiritApiBundle\Response\ApiResponse $response */
            if($response->getIsError() === false){
                $result = true;
            }
        }
        return $result;
    }

    /**
     * {inheritdoc}
     */
    public function classToMap(): string
    {
        return WebAccount::class;
    }
}
