<?php

namespace Edcoms\SpiritApiBundle\Call;

/**
 * A object containing the necessary data to make an API endpoint call.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class ApiCall
{
    /**
     * @var  bool
     */
    protected $absoluteUri = false;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var array
     */
    protected $queryData;

    /**
     * @param  string  $method  HTTP method to use when making call.
     * @param  string  $uri     The endpoint URI of the call.
     * @param  array   $data    Any data to send with the call. ($method must be 'POST' or 'PUT').
     * @param  array   $queryData    Query path data).
     */
    public function __construct(string $method, string $uri, array $data = [], array $queryData = [])
    {
        if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
            throw new \InvalidArgumentException("Unsupported HTTP method '$method'.");
        }

        $this->data = $data;
        $this->method = $method;
        $this->uri = $uri;
        $this->queryData = $queryData;
    }

    /**
     * @param  bool  $absoluteUri  Get absolute uri.
     */
    public function getUriAbsolute(): bool
    {
        return $this->absoluteUri;
    }

    /**
     * @param  bool  $absoluteUri  'true' if the uri parameter is an absolute URL.
     */
    public function setUriAbsolute(bool $absoluteUri)
    {
        $this->absoluteUri = $absoluteUri;
    }

    /**
     * @return  array  The data to send with this AP( call.)
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return  array  The HTTP method to use when making this API call.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return  array  The endpoint URI of this API call.
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getQueryData(): array {
        return $this->queryData;
    }

}
