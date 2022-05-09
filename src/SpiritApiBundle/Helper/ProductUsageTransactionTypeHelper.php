<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2018-01-03 12:35:09
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:28:12
 */
namespace Edcoms\SpiritApiBundle\Helper;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Caller\ApiCaller;
use Edcoms\SpiritApiBundle\Helper\AbstractHelper;
use Edcoms\SpiritApiBundle\Model\ProductUsageTransactionType;
use Edcoms\SpiritApiBundle\Response\ApiResponse;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Edcoms\SpiritApiBundle\Call\ProductUsageTransactionTypeCall;

/**
 * Service used to make API calls.
 * This particular service is dedicated to make calls relating to Product Usage Transaction Type.
 *
 * @see  http://apidocs.educationcompany.co.uk/#product-usage
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class ProductUsageTransactionTypeHelper extends AbstractHelper
{
    /**
     * Calls the SPIRIT service to create a Product Usage Transaction Type.
     * Ref: http://apidocs.educationcompany.co.uk/#product-usage-transaction-type-create
     *
     * @param   ProductUsageTransactionType  $productUsageTransactionType  The ProductUsageTransactionType to create.
     *
     * @return  ProductUsageTransactionType|BadApiResponse                 '$productUsageTransactionType' populated with the newly created ProductUsageTransactionType ID. If error, the json response error message.
     */
    public function createProductUsageTransactionType(ProductUsageTransactionType $productUsageTransactionType)
    {
        $productUsageTransactionType = $this->normalizeModel($productUsageTransactionType);
        $apiCall = ProductUsageTransactionTypeCall::Create($productUsageTransactionType);

        $response = $this->makeCall($apiCall);

        // continues if an exception hasn't already been thrown.
        if ($response instanceof BadApiResponse) {
            return $response;
        }

        $productUsageTransactionType->setId($response->getData()); //need to check that repsonse is just the product ID.

        return $productUsageTransactionType;
    }

    /**
     * Calls the SPIRIT service to update a Product Usage Transaction Type.
     * Ref: http://apidocs.educationcompany.co.uk/#product-usage-transaction-type-update
     *
     * @param   ProductUsageTransactionType  $productUsageTransactionType  The ProductUsageTransactionType to update.
     *
     * @return  ProductUsageTransactionType|BadApiResponse                 '$productUsageTransactionType' populated with the updated fields returned spirit. If error, the json response error message.
     */
    public function updateProductUsageTransactionType(ProductUsageTransactionType $productUsageTransactionType)
    {
        $productUsageTransactionType = $this->normalizeModel($productUsageTransactionType);
        $apiCall = ProductUsageTransactionTypeCall::Update($productUsageTransactionType);

        $response = $this->makeCall($apiCall);

        // continues if an exception hasn't already been thrown.
        if ($response instanceof BadApiResponse) {
            return $response;
        }

        return $productUsageTransactionType;
    }    


    /**
     * Calls the SPIRIT service to retrieve all Product Usage Transaction Type objects.
     * Ref: http://apidocs.educationcompany.co.uk/#product-usage-transaction-type-update
     *
     * @return  ArrayCollection|null  The returned Product Usage Transaction Type array from the SPIRIT service, or null if none found.
     */
    public function getAll()
    {
        $apiCall = new ApiCall('GET', '/ProductUsage/TransactionTypes');

        return $this->makeCallAndMapResponse($apiCall);
    }

    /**
     * TODO: implement when SPIRIT implements this endpoint in their API.
     * Calls the SPIRIT service to retrieve a Product Usage Transaction Type object.
     * 
     * @param   string  $productUsageTransactionTypeId  Id of the product usage transaction type to return, if any.
     *
     * @return  ProductUsageTransactionType|null        The returned ProductUsageTransactionType, or null if none.
     */
    public function getById(string $productUsageTransactionTypeId)
    {
        throw new \Exception('This method has not been implemented.');
        //there is no getById for productusagetransactiontype listed in API documentation - this is for future use (if it is created).
        
    
        $apiCall = new ApiCall(
            'GET',
            "/ProductUsage/TransactionTypes/{$productUsageTransactionTypeId}"
        );
    
        return $this->makeCallAndMapResponse($apiCall);
    }

    /**
     * {inheritdoc}
     */
    public function classToMap(): string
    {
        return ProductUsageTransactionType::class;
    }
}
