<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2018-01-03 12:35:09
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:28:00
 */
namespace Edcoms\SpiritApiBundle\Helper;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Call\ProductUsageCall;
use Edcoms\SpiritApiBundle\Caller\ApiCaller;
use Edcoms\SpiritApiBundle\Helper\AbstractHelper;
use Edcoms\SpiritApiBundle\Model\ProductUsage;
use Edcoms\SpiritApiBundle\Response\ApiResponse;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;

/**
 * Service used to make API calls.
 * This particular service is dedicated to make calls relating to Product Usage.
 *
 * @see  http://apidocs.educationcompany.co.uk/#product-usage
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class ProductUsageHelper extends AbstractHelper
{
    /**
     * Calls the SPIRIT service to create a Product Usage Transaction Type.
     * Ref: http://apidocs.educationcompany.co.uk/#product-usage-create
     *
     * @param   ProductUsage  $productUsage  The ProductUsage to create.
     *
     * @return  ProductUsage|BadApiResponse  '$productUsage' populated with the newly created ProductUsage ID. If error, the json response error message.
     */
    public function createProductUsage(ProductUsage $productUsage)
    {
        $productUsage = $this->normalizeModel($productUsage);
        $apiCall = ProductUsageCall::Create($productUsage);

        $response = $this->makeCall($apiCall);

        // continues if an exception hasn't already been thrown.
        if ($response instanceof BadApiResponse) {
            return $response;
        }

        if (property_exists($response->getAdditionalData(), 'ProductUsageId')) {
            $productUsage->setId($response->getAdditionalData()->ProductUsageId);    
        }

        return $productUsage;
    }

    /**
     * Calls the SPIRIT service to retrieve Product Usage Sum of Value for a set of user defined params.
     * Ref: http://apidocs.educationcompany.co.uk/#product-usage-sum-of-value-load
     *
     * @return  ArrayCollection|null  The returned Product Usage Sum of Value from the SPIRIT service, or null if none found.
     */
    public function getProductUsageSumOfValue(string $productId, string $orgId, string $personId, string $webAccountId, string $transactionDateStart, string $transactionDateEnd, string $transactionTypeId, string $contentId)
    {
        $apiCallData = [
            'productId' => $productId,
            'orgId' => $orgId,
            'personId' => $personId,
            'webAccountId' => $webAccountId,
            'transactionDateStart' => $transactionDateStart,
            'transactionDateEnd' => $transactionDateEnd,
            'transactionTypeId' => $transactionTypeId,
            'contentId' => $contentId
        ];

        $apiCall = new ApiCall(
            'GET',
            "/ProductUsage/SumOfValue",
            $apiCallData
        );
    
        return $this->makeCallAndMapResponse($apiCall);
    }

    /**
     * TODO: implement when SPIRIT implements this endpoint.
     * Calls the SPIRIT service to retrieve a Product Usage object.
     * 
     * @param   string  $productUsage  Id of the product usage transaction type to return, if any.
     *
     * @return  ProductUsage|null      The returned ProductUsage, or null if none.
     */
    public function getById(string $productUsageId)
    {
        throw new \Exception('This method has not been implemented.');
        //there is no getById for products listed in API documentation - this is for future use (if it is created).


        // NB: This is not currently available in SPIRIT API but wil probably be added. So this is for future use, when added.
        $apiCall = new ApiCall('GET', "/ProductUsage/TransactionTypes/{$productUsageId}");
    
        return $this->makeCallAndMapResponse($apiCall);
    }

    /**
     * {inheritdoc}
     */
    public function classToMap(): string
    {
        return ProductUsage::class;
    }
}
