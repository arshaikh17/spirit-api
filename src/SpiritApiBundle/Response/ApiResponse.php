<?php

namespace Edcoms\SpiritApiBundle\Response;

use GuzzleHttp\Psr7\Response;
use Edcoms\SpiritApiBundle\Call\ApiCall;

/**
 * A object containing the data received from an API endpoint call.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class ApiResponse
{
    /**
     * @var  ApiCall
     */
    protected $call;

    /**
     * @var  int
     */
    protected $code;

    /**
     * @var  mixed
     */
    protected $data;

    /**
     * @var  mixed
     */
    protected $additionalData;

    /**
     * @var  boolean
     */
    protected $isError;

    /**
     * @var  string
     */
    protected $message;

    /**
     * @var  Response
     */
    protected $response;

    /**
     * @param  ApiCall        $call      Object detailing the originating API call.
     * @param  Response|null  $response  Response object from HTTP client.
     */
    public function __construct(ApiCall $call, Response $response = null)
    {
        $this->call = $call;
        $this->response = $response;

        if ($this->response !== null) {
            $this->processResponse($this->response);
        }
    }

    /**
     * Populates the properties from the data contained in '$response'.
     * It also checks the 'Content-Type' header, where if the content type is supported by this method,
     * the data is decoded according the content type value.
     *
     * Supported:
     *     - 'application/json'
     *
     * @param  Response  $response  Data to process.
     */
    protected function processResponse(Response $response)
    {
        $contentType = $response->hasHeader('Content-Type') ? $response->getHeader('Content-Type')[0] : 'plain/text';

        if (0 !== strpos($contentType, 'application/json')) {
            return;
        }

        $jsonData = json_decode($response->getBody());

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Data received from SPIRIT does not contain valid JSON.');
        }

        $this->code = isset($jsonData->Code) ? $jsonData->Code : -1;
        $this->message = $jsonData->Message; // always expecting a message.
        $this->isError = isset($jsonData->IsError) ? $jsonData->IsError : $response->getStatusCode() < 400;
        $this->data = isset($jsonData->Response) ? $jsonData->Response : null;
        $this->additionalData = isset($jsonData->AdditionalData) ? $jsonData->AdditionalData : null;
    }

    /**
     * @return  int  Get code.
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param  int  $code  Set code.
     */
    public function setCode(int $code)
    {
        $this->code = $code;
    }

    /**
     * @return  string  Get message.
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param  string  $message  Set message.
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return  bool  Get is error.
     */
    public function getIsError(): bool
    {
        return $this->isError;
    }

    /**
     * @param  bool  $isError  Set is error.
     */
    public function setIsError(bool $isError)
    {
        $this->isError = $isError;
    }

    /**
     * @return  mixed  Get data.
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return  mixed  Get AdditionalData.
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }    
}
