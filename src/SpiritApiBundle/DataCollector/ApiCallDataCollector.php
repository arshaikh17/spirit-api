<?php

namespace Edcoms\SpiritApiBundle\DataCollector;

use Edcoms\SpiritApiBundle\Caller\ApiCaller;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Data collector used to profile calls to the SPIRIT API service.
 *
 * @author James Stubbs <james.stubbs@edcoms.co.uk>
 */
class ApiCallDataCollector extends DataCollector
{
    /**
     * @var  string
     */
    private $apiKey;

    /**
     * @var  ApiCaller
     */
    private $caller;

    /**
     * @param  string     $apiKey     The API key used to authenticate each SPIRIT request.
     * @param  ApiCaller  $apiCaller  The API calling service.
     */
    public function __construct(ApiCaller $apiCaller, string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->caller = $apiCaller;
    }

    /**
     * {inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $calls = $this->caller->getCallsMade();
        $callsData = [];
        $erroredCallsData = [];
        $totalTime = 0.0;

        foreach ($calls as $call) {
            $apiCall = $call['call'];
            $apiResponse = $call['response'];
            $data = [
                'call' => $apiCall,
                'code' => $apiResponse->getCode(),
                'data' => $apiResponse->getData(),
                'endpoint' => $apiCall->getUri(),
                'isError' => $apiResponse->getIsError(),
                'message' => $apiResponse->getMessage(),
                'method' => $apiCall->getMethod(),
                'time' => $call['time']
            ];

            $callsData[] = $data;
            $totalTime = $totalTime + $call['time'];

            if ($data['isError']) {
                $erroredCallsData[] = $data;
            }
        }

        $this->data['apiKey'] = $this->apiKey;
        $this->data['baseUrl'] = $this->caller->getBaseUrl();
        $this->data['calls'] = $callsData;
        $this->data['errors'] = $erroredCallsData;
        $this->data['status'] = $this->caller->getStatus();
        $this->data['time'] = $totalTime;
    }

    /**
     * @return  string  Get API key.
     */
    public function getApiKey(): string
    {
        return $this->data['apiKey'];
    }

    /**
     * @return  string  Get base URL.
     */
    public function getBaseUrl(): string
    {
        return $this->data['baseUrl'];
    }

    /**
     * @return  array  The API calls made.
     */
    public function getCalls(): array
    {
        return $this->data['calls'];
    }

    /**
     * @return  int  Number of API calls made.
     */
    public function getCallsCount(): int
    {
        return count($this->data['calls']);
    }

    /**
     * @return  int  Number of errored API calls made.
     */
    public function getErrorsCount(): int
    {
        return count($this->data['errors']);
    }

    /**
     * {inheritdoc}
     */
    public function getName()
    {
        return 'spirit_api.call_collector';
    }

    /**
     * @return  bool  Get SPIRIT is online.
     */
    public function getStatus(): string
    {
        return isset($this->data['status']) ? $this->data['status'] : 'not_checked';
    }

    /**
     * @return  bool  Get total time.
     */
    public function getTotalTime(): float
    {
        return $this->data['time'];
    }

    /**
     * @return  bool  Get has errors.
     */
    public function hasErrors(): bool
    {
        return !empty($this->data['errors']);
    }
}
