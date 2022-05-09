<?php

namespace Edcoms\SpiritApiBundle\Tests\Response\ApiResponseTest;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Response\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Unit test for the ApiResponse class.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class ApiResponseTest extends WebTestCase
{
    public function testJsonDecoding()
    {
        $jsonTestData = $this->getTestData();

        $mockResponse = new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($jsonTestData)
        );
        $apiCall = $this->createMock(ApiCall::class);
        $apiResponse = new ApiResponse($apiCall, $mockResponse);

        $this->assertNotNull($apiResponse->getData());
        $this->assertEquals($apiResponse->getData(), $jsonTestData->Response);
    }

    public function testJsonDecodingError()
    {
        $jsonTestData = $this->getTestData();

        $mockResponse = new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($jsonTestData) . '}}'
        );
        $apiCall = $this->createMock(ApiCall::class);

        $this->expectException(\Exception::class);
        $apiResponse = new ApiResponse($apiCall, $mockResponse);
    }

    protected function getTestData()
    {
        $objVal = new \stdClass();
        $objVal->int_val = 789;
        $objVal->string_val = 'bye';
        $objVal->bool_val = false;

        $responseObj = new \stdClass();
        $responseObj->int_val = 123;
        $responseObj->string_val = 'hi';
        $responseObj->bool_val = true;
        $responseObj->array_val = [1, 'a', true];
        $responseObj->obj_val = $objVal;

        $data = new \stdClass();
        $data->Code = 1;
        $data->IsError = false;
        $data->Message = 'message here';
        $data->Response = $responseObj;
        
        return $data;
    }
}
