<?php

namespace Edcoms\SpiritApiBundle\Caller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Caller\ApiCallerInterface;
use Edcoms\SpiritApiBundle\Event\SpiritCallEvent;
use Edcoms\SpiritApiBundle\Event\SpiritEvents;
use Edcoms\SpiritApiBundle\Exception\ApiCallException;
use Edcoms\SpiritApiBundle\Response\ApiResponse;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * API calling service which takes an instance of ApiCall and makes an HTTP request to the SPIRIT service.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class ApiCaller implements ApiCallerInterface
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var  EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var  array
     */
    protected $callsMade = [];

    /**
     * @var  array
     */
    protected $options;

    /**
     * @var  string
     */
    protected $status = 'not_checked';

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $version;

    /**
     * @param  EventDispatcherInterface  $eventDispatcher  The event dispatcher service.
     * @param  string                    $apiKey           API key used to authenticate each SPIRIT API call.
     * @param  string                    $url              The base URL to connect to the SPIRIT API.
     * @param  string                    $version          The SPIRIT API version to use. This will be included in the URL to make calls with.
     * @param  string                    $prefix           A string to append to the end of the base URL with each request.
     * @param  array                     $options          Any HTTP request options to pass to the client making the calls.
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, string $apiKey, string $url, string $version, string $prefix, array $options = [])
    {
        $this->apiKey = $apiKey;
        $this->eventDispatcher = $eventDispatcher;
        $this->prefix = $prefix;
        $this->options = $options;
        $this->url = $url;
        $this->version = $version;
    }

    /**
     * {inheritdoc}
     */
    public function makeCall(ApiCall $apiCall, bool $throwExceptionOnError = true): ApiResponse
    {
        $apiResponse = null;
        $caughtException = null;
        $request = $this->createRequestFromCall($apiCall);

        // assume service is online unless we receive a 'ConnectonException'.
        $this->status = 'online';
        $startTime = microtime(true);

        try {
            $timeout = isset($this->options[RequestOptions::CONNECT_TIMEOUT]) ? $this->options[RequestOptions::CONNECT_TIMEOUT] : 10;

            $client = new Client();
            $requestOptions = [
                RequestOptions::CONNECT_TIMEOUT => $timeout,
            ];

            if (isset($this->options['proxy'])
                && isset($this->options['proxy']['scheme'])
                && isset($this->options['proxy']['base_url'])
                && !empty($this->options['proxy']['base_url'])
            ) {
                $requestOptions[RequestOptions::PROXY] = [
                    $this->options['proxy']['scheme'] => "{$this->options['proxy']['scheme']}://{$this->options['proxy']['base_url']}"
                ];
            }

            $response = $client->send($request, $requestOptions);

            $apiResponse = new ApiResponse($apiCall, $response);

            if ($apiResponse->getIsError()) {
                $caughtException = new ApiCallException($apiResponse->getMessage());
            }
        } catch (ConnectException $e) {
            $caughtException = $e;

            $apiResponse = new BadApiResponse($apiCall);
            $apiResponse->setCode(-1);
            $apiResponse->setMessage('Cannot connect to SPIRIT API service.');

            $this->status = 'offline';
        } catch (RequestException $e) {
            $caughtException = $e;

            $apiResponse = new BadApiResponse($apiCall, $e->getResponse());
        }

        $endTime = microtime(true);

        $this->callsMade[] = [
            'call' => $apiCall,
            'response' => $apiResponse,
            'time' => ($endTime - $startTime) * 1000, // in milli-seconds.
        ];

        // dispatch the SPIRIT call event.
        $event = new SpiritCallEvent($apiResponse);
        $this->eventDispatcher->dispatch(SpiritEvents::CALL, $event);

        if ($throwExceptionOnError && $caughtException !== null) {
            if (!$caughtException instanceof ApiCallException) {
                $caughtException = new ApiCallException($caughtException->getMessage());
                $caughtException->setResponse($apiResponse);
            }

            throw $caughtException;
        }

        return $apiResponse;
    }

    /**
     * {inheritdoc}
     */
    public function getBaseUrl(): string
    {
        $prefix = ltrim($this->prefix, '/');

        return $url = "{$this->url}{$this->version}/{$prefix}";
    }

    /**
     * @return  array  Get calls made.
     */
    public function getCallsMade(): array
    {
        return $this->callsMade;
    }

    /**
     * @return  string  Get API status.
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Creates a PSR7 Request object from the data contained in '$apiCall'.
     *
     * @param   ApiCall  $apiCall  The call to retrieve the request details from.
     *
     * @return  Request            The created PSR 7 request object.
     */
    protected function createRequestFromCall(ApiCall $apiCall): Request
    {
        $method = $apiCall->getMethod();
        $callUri = ltrim($apiCall->getUri(), '/');
        $baseUrl = $apiCall->getUriAbsolute() ? '' : $this->getBaseUrl().'/';
        $url = "{$baseUrl}{$callUri}?apiKey={$this->apiKey}";

        $queryData = $apiCall->getQueryData();

        if($queryData && count($queryData)>0){
            $queryPath = '';
            foreach ($queryData as $key => $qpd){
                $queryPath .= sprintf('&%s=%s', $key, $qpd);
            }
            $url .= $queryPath;
        }

        $headers = [];
        $body = null;

        $data = $apiCall->getData();

        if ($method === 'GET' && !empty($data)) {
            // the HTTP client won't handle the body data if the HTTP method is 'GET'.
            // so here, we'll manually append the GET data.
            $getData = [];

            foreach ($data as $key => $value) {

                if (is_array($value)) {
                    $getData[] = urlencode($key) . '=' . urlencode(serialize($value)); 
                } else {
                    $getData[] = urlencode($key) . '=' . urlencode($value);
                }
            };

            $url .= '&' . implode('&', $getData);
        } elseif ($method === 'POST' || $method === 'PUT' || $method === 'DELETE') {
            // the SPIRIT API takes a JSON body when making a 'POST' or 'PUT' or 'DELETE' request.
            $headers['Content-Type'] = 'application/json';

            $body = json_encode($data ?: []);
        }

        return new Request(
            $method,
            $url,
            $headers,
            $body
        );
    }
}
