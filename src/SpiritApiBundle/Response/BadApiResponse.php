<?php

namespace Edcoms\SpiritApiBundle\Response;

use GuzzleHttp\Psr7\Response;
use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Response\ApiResponse;

/**
 * A object containing the data received from an API endpoint call.
 * Sub-classed so that any bad responses received from the HTTP client
 * can be recorded and stored in an instance of this class.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class BadApiResponse extends ApiResponse
{
    /**
     * @var  Throwable
     */
    protected $exception;

    /**
     * {inheritdoc}
     */
    public function __construct(ApiCall $call, Response $response = null)
    {
        parent::__construct($call, $response);

        $this->isError = true;
    }

    /**
     * @return  Throwable  Get exception.
     */
    public function getException(): \Throwable
    {
        return $this->exception;
    }

    /**
     * @param  Throwable  $exception  Set exception.
     */
    public function setException(\Throwable $exception)
    {
        $this->exception = $exception;
    }
}
