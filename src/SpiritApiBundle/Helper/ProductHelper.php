<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2018-01-03 12:35:09
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:27:53
 */
namespace Edcoms\SpiritApiBundle\Helper;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Call\ProductCall;
use Edcoms\SpiritApiBundle\Caller\ApiCaller;
use Edcoms\SpiritApiBundle\Helper\AbstractHelper;
use Edcoms\SpiritApiBundle\Model\Product;
use Edcoms\SpiritApiBundle\Response\ApiResponse;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;

/**
 * Service used to make API calls.
 * This particular service is dedicated to make calls relating to Product Usage.
 *
 * @see  http://apidocs.educationcompany.co.uk/#products-2
 * @author   Daniel Forer <daniel@forermedia.com>
 */
class ProductHelper extends AbstractHelper
{
    /**
     * Calls the SPIRIT service to create a Product.
     * Ref: http://apidocs.educationcompany.co.uk/#product-load
     *
     * @param   Product  $product       The Product to create.
     *
     * @return  Product|BadApiResponse  '$product' populated with the newly created Product ID. If error, the json response error message.
     */
    public function createProduct(Product $product)
    {
        $product = $this->normalizeModel($product);
        $apiCall = ProductCall::Create($product);

        $response = $this->makeCall($apiCall);

        // continues if an exception hasn't already been thrown.
        if ($response instanceof BadApiResponse) {
            return $response;
        }

        $product->setId($response->getData()); //need to check that repsonse is just the produdct ID.

        return $product;
    }

    /**
     * 
     * Calls the SPIRIT service to retrieve a list of all Products.
     * Ref: http://apidocs.educationcompany.co.uk/#1960
     * correction: endpoint is /Products and not Products/ListProducts as documented.
     *
     * @param   string  $productIds     Array of SPIRIT ID of the Products to retrieve, if null all products are retrieved.
     * @param   int     $maxRecords     Max number of records to return in response (useful for paging). Default max is 2000.
     * @param   int     $skipRecords    Number of skipped records in return response (useful for paging). If none, 0-2000 return. If 2000, then 2000-4000 returned and so on.
     *
     * @return  Product[]       Collection of found products.
     */
    public function listProducts(array $productIds = null, int $maxRecords = null, int $skipRecords = null)
    {

        $apiCallData = array();

        if ($productIds !== null) {
            $ids = implode(",", $productIds);
            $apiCallData['productIds'] = $ids;
        }

        if ($maxRecords !== null) {
            $apiCallData['maxRecords'] = $maxRecords;
        }

        if ($skipRecords !== null) {
            $apiCallData['skipRecords'] = $skipRecords;
        }        

        $apiCall = new ApiCall('GET', '/Products', $apiCallData);

        return $this->makeCallAndMapResponse($apiCall);
        
    }


    /**
     * TODO: needs implementation of endpoint by EdCo on spirit API.
     * Calls the SPIRIT service to retrieve a Product object by the ID.
     *
     * @param   string $productId  SPIRIT ID of the Product object to retrieve.
     *
     * @return  Product|null       The returned Product from the SPIRIT service, or null if not found.
     */
    public function getById(string $productId, bool $includeUserDefinedFields = false)
    {

        throw new \Exception('This method has not been implemented.');
        //there is no getById for products listed in API documentation - this is for future use (if it is created).
    }

    /**
     * {inheritdoc}
     */
    public function classToMap(): string
    {
        return Product::class;
    }
}
