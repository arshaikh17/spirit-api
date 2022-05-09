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
class WebAccountCall extends ApiCall
{
    /**
     * Creates an ApiCall object, so that it can be used to call the SPIRIT service to create a WebAccount.
     *
     * @param   WebAccount  $webAccount  The model object to grab the data from to send to SPIRIT.
     *
     * @return  self                     The created ApiCall object.
     */
    public static function create(WebAccount $webAccount): self
    {
        $data = WebAccountCall::webAccountPayload($webAccount);

        $apiCall = new self('POST', '/WebAccounts', $data);

        return $apiCall;
    }


    /**
     * Creates an ApiCall object, so that it can be used to call the SPIRIT service to update a WebAccount.
     *
     * @param   WebAccount  $webAccount  The model object to grab the data from to send to SPIRIT.
     *
     * @return  self                     The updated ApiCall object.
     */
    public static function update(WebAccount $webAccount, $updateOrganisation = false): self
    {
        $data = WebAccountCall::webAccountPayload($webAccount, true, $updateOrganisation);

        $apiCall = new self('PUT', '/WebAccounts/' . $webAccount->getId(), $data);

        return $apiCall;
    }


    /**
     * Creates an ApiCall object, so that it can be used to call the SPIRIT service to update a WebAccount.
     *
     * @param   WebAccount  $webAccount  The model object to grab the data from to send to SPIRIT.
     *
     * @return  self                     The updated ApiCall object.
     */
    public static function updatePassword($webAccountId, $newPassword): self
    {
        $data = WebAccountCall::updatePasswordPayload($webAccountId, $newPassword);

        $apiCall = new self('POST', '/WebAccounts/UpdatePassword', $data);

        return $apiCall;
    }


    private static function updatePasswordPayload($webAccountId, $newPassword) 
    {
        $data = [
                'WebAccountId' => $webAccountId,
                'NewPassword' => $newPassword,
        ];

      return $data;
    }

    /**
     * Creates an ApiCall object, so that it can be used to call the SPIRIT service to authenticate a WebAccount.
     *
     * @param   WebAccount  $webAccount  The model object to grab the data from to send to SPIRIT.
     *
     * @return  self                     The created ApiCall object.
     */
    public static function authenticate($username, $password, $webAccountUserTypeId): self
    {
        $data = WebAccountCall::authenticatePayload($username, $password, $webAccountUserTypeId);

        $apiCall = new self('POST', '/WebAccounts/Authenticate', $data);

        return $apiCall;
    }   

    private static function authenticatePayload($username, $password, $webAccountUserTypeId) 
    {     
        //creating WebAccount Authenticate payload.
        $data = [
            'Username' => $username,
            'Password' => $password,
            'WebAccountTypeId' => $webAccountUserTypeId
        ];

        return $data;
    }

    private static function webAccountPayload(WebAccount $webAccount, $isUpdate = false, $updateOrganisation = false) 
    {
        $person = $webAccount->getPerson();
        $organisation = $webAccount->getOrganisation();

        if ($isUpdate) {
            //all values in this payload are required for update - ignore EdCo documentation saying otherwise.
            $data = [
                'UserName' => $webAccount->getUsername(),
                'EmailAddress' => $webAccount->getEmail(),
                'ScreenName' => $webAccount->getScreenName(),
                'WebUserTypeId' => $webAccount->getUserType()->getId(),
                'WebAccountTypeId' => $webAccount->getType()->getId(),
                'PersonId' => $person->getId(),
                'OrgId' => $organisation->getId(),
                'OrgTypeId' => $organisation->getType()->getId(),                
                'AccountSuspended' => false
            ];

            //person data required.
            $data['PersonTitle'] = $person->getTitle();
            $data['PersonFirstName'] = $person->getFirstName();
            $data['PersonLastName'] = $person->getLastName();
            $data['PersonJobTitle'] = $person->getJobTitle();
            $data['PersonJobTypeId'] = $person->getJobType()->getId();

            //NB: we do not update organisation details typically - this is handled via spirit. This is for versatility.
            if ($updateOrganisation) {
                $data['OrganisationName'] = $organisation->getName();
                $data['AddressLine1'] = $organisation->getAddress1();
                $data['AddressLine2'] = $organisation->getAddress2();
                $data['AddressLine3'] = $organisation->getAddress3();
                $data['Town'] = $organisation->getTown();
                $data['Region'] = $organisation->getRegion();
                $data['Postcode'] = $organisation->getPostcode();
                /** @TODO get the country property below to be an object not just an int
                 * gitId() removed from the country as the value is passed from the reg form as an int */
                if ($organisation->getCountry() !== null) {
                    $data['CountryId'] = empty($organisation->getCountry()->getId()) ? 1 : $organisation->getCountry()->getId();    
                }
                $data['OrgTypeId'] = $organisation->getType()->getId();
            }
        } else {
            //creating WebAccount.
            $data = [
                'UserName' => $webAccount->getUsername(),
                'EmailAddress' => $webAccount->getEmail(),
                'Password' => $webAccount->getPassword(),
                'ScreenName' => $webAccount->getScreenName(),
                'WebUserTypeId' => $webAccount->getUserType()->getId(),
                'WebAccountTypeId' => $webAccount->getType()->getId(),
                'PersonId' => $person->getId(),
                'OrgId' => $organisation->getId(),
                'AccountSuspended' => false,
                'OrgTypeId' => $organisation->getType()->getId()                
            ];

            // add Person details if we're not referring to an existing Person object.
            if (null === $person->getId()) {
                $data['PersonTitle'] = $person->getTitle();
                $data['PersonFirstName'] = $person->getFirstName();
                $data['PersonLastName'] = $person->getLastName();
                $data['PersonJobTitle'] = $person->getJobTitle();
                $data['PersonJobTypeId'] = $person->getJobType()->getId();
            }

            // add Organisation details if we're not referring to an existing Organisation object.
            if (null === $organisation->getId()) {
                $data['OrganisationName'] = $organisation->getName();
                $data['AddressLine1'] = $organisation->getAddress1();
                $data['AddressLine2'] = $organisation->getAddress2();
                $data['AddressLine3'] = $organisation->getAddress3();
                $data['Town'] = $organisation->getTown();
                $data['Region'] = $organisation->getRegion();
                $data['Postcode'] = $organisation->getPostcode();
                /** @TODO get the country property below to be an object not just an int
                 * gitId() removed from the country as the value is passed from the reg form as an int */
                $data['CountryId'] = $organisation->getCountry();
                $data['OrgTypeId'] = $organisation->getType()->getId();
            }
        }

        return $data;
    }

}
