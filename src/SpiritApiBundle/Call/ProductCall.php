<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2018-01-03 12:35:09
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:25:26
 */
namespace Edcoms\SpiritApiBundle\Call;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Model\Product;

/**
 * An object containing the necessary data to make API endpoint call to create a new Product.
 *
 * @see  http://apidocs.educationcompany.co.uk/#products-2
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class ProductCall extends ApiCall
{
    /**
     * Creates an ApiCall object, so that it can be used to call the SPIRIT service to create a Product.
     *
     * @param   Product  $product  The model object to grab the data from to send to SPIRIT.
     *
     * @return  apiCall            The created ApiCall object.
     */
    public static function create(Product $product)
    {
        $apiCallData = [
            'Name' => $product->getName(),
            'Code' => $product->getCode(),
            'DisplayName' => $product->getDisplayName(),
            'LookupCode' => $product->getLookupCode(),
            'Description' => $product->getDescription(),
            'ShowOnWebSite' => $product->getShowOnWebSite(),
            'Digital' => $product->getDigital(),
            'ContainsDigitalAssets' => $product->getContainsDigitalAssets(),
            'Stock' => $product->getStock(),
            'AcceptBackOrders' => $product->getAcceptBackOrders(),
            'PublicationDate' => $product->getPublicationDate(),
            'ReprintDate' => $product->getReprintDate(),
            'Subscription' => $product->getSubscription(),
            'SubscriptionLengthDays' => $product->getSubscriptionLengthDays(),
            'Pipeline' => $product->getPipeline(),
            'Attributes' => $product->getAttributes() //array
        ];

        $apiCall = new ApiCall('POST', '/Products', $apiCallData); //creates based on configured URL.

        return $apiCall;
    }
}
