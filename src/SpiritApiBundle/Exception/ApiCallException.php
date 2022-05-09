<?php

namespace Edcoms\SpiritApiBundle\Exception;

use Edcoms\SpiritApiBundle\Response\ApiResponse;

/**
 * Exception class thrown upon receiving a bad HTTP response.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class ApiCallException extends \Exception
{
    /**
     * @var  ApiResponse
     */
    protected $response;

    /**
     * @return  ApiResponse  Get the PSR response object.
     */
    public function getResponse(): ApiResponse
    {
        return $this->response;
    }

    /**
     * @param  ApiResponse  $response  Set the PSR response object.
     */
    public function setResponse(ApiResponse $response)
    {
        $this->response = $response;
    }
}
