<?php

namespace Edcoms\SpiritApiBundle\Helper;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Caller\ApiCaller;
use Edcoms\SpiritApiBundle\Helper\AbstractHelper;
use Edcoms\SpiritApiBundle\Model\Country;

/**
 * Service used to make API calls.
 * This particular service is dedicated to make calls relating to Countries.
 *
 * @see  http://apidocs.educationcompany.co.uk/#countries
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class CountryHelper extends AbstractHelper
{
    /**
     * Ref: http://apidocs.educationcompany.co.uk/#countries-api-get
     * @return  Country[]  A list of countries contained within the SPIRIT API service.
     */
    public function getAll()
    {
        $apiCall = new ApiCall(
            'GET',
            "/Countries"
        );

        $countries = $this->makeCallAndMapResponse($apiCall);

        // strip out any countries that have been marked by SPIRIT not to be used.
        if (is_array($countries)) {
            $countries = array_filter($countries, function ($country) {
                return strpos($country->name, 'ZZZ DO NOT USE') === false;
            });
        }

        return $countries;
    }

    /**
     * @return  array  A list of countries contained within the SPIRIT API service, formatted as a choice array.
     */
    public function getAllAsChoices()
    {
        $countries = [];

        foreach ($this->getAll() as $country) {
            $countries[$country->name] = $country->id;
        }

        return $countries;
    }

    /*
     *  TODO: the related spirit api endpoint for this function is yet to be implemented.
     *
     * 
     */
    public function getById(string $countryId, bool $includeUserDefinedFields = false)
    {
        throw new \Exception('This method has not been implemented.');
        
        $apiCall = new ApiCall(
            'GET',
            "/Countries/{$countryId}"
        );

        return $this->makeCallAndMapResponse($apiCall);
    }

    /**
     * {inheritdoc}
     */
    public function classToMap(): string
    {
        return Country::class;
    }
}
