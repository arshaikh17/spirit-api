<?php

namespace Edcoms\SpiritApiBundle\Caller;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Response\ApiResponse;

/**
 * The interface of the API calling service which takes an instance of ApiCall,
 * and makes an HTTP request to the SPIRIT service.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
interface ApiCallerInterface
{
    /**
     * @return  string  Get base URL.
     */
    public function getBaseUrl(): string;

    /**
     * Makes a SPIRIT API call and returns the response.
     *
     * @param   ApiCall  $apiCall                Details of the call to make against the SPIRIT API service.
     * @param   bool     $throwExceptionOnError  Overriding the setting to make the caller throw an exception upon error.
     *
     * @return  ApiResponse        Details of the response from the SPIRIT API service.
     */
    public function makeCall(ApiCall $apiCall, bool $throwExceptionOnError = true): ApiResponse;
}
