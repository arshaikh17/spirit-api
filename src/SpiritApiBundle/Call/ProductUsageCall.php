<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2018-01-03 12:35:09
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:25:34
 */
namespace Edcoms\SpiritApiBundle\Call;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Model\ProductUsage;

/**
 * An object containing the necessary data to make API endpoint call to create a new Product Usage.
 *
 * @see  http://apidocs.educationcompany.co.uk/#products-usage
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class ProductUsageCall extends ApiCall
{
    /**
     * Creates an ApiCall object, so that it can be used to call the SPIRIT service to create a Product Usage.
     *
     * @param   ProductUsage  $productUsage  The model object to grab the data from to send to SPIRIT.
     *
     * @return  ApiCall                      The created ApiCall object.
     */
    public static function create(ProductUsage $productUsage)
    {
        $apiCallData = [
            'ProductId' => $productUsage->getProduct()->getId(),
            'OrgId' => $productUsage->getOrganisation()->getId(),
            'PersonId' => $productUsage->getPerson()->getId(),
            'WebAccountId' => $productUsage->getWebAccount()->getId(),
            'TransactionDate' => $productUsage->getTransactionDate(),
            'Value' => $productUsage->getValue(),
            'TransactionTypeId' =>$productUsage->getTransactionType()->getId(),
            'ContentId' => $productUsage->getContentId()
        ];

        $apiCall = new ApiCall('POST', '/ProductUsage', $apiCallData);

        return $apiCall;
    }

    /**
     * Creates an ApiCall object, so that it can be used to call the SPIRIT service to get the Product Usage Sum of Value.
     *
     * Ref: http://apidocs.educationcompany.co.uk/#product-usage-sum-of-value-load
     * 
     * @param   params  $sumParams  The model object to grab the data from to send to SPIRIT.
     *
     * @return  self                The created ApiCall object.
     */
    public static function sumOfValues(array $sumParams): self
    {


        //sumParams need to be added to the URL below.
        $apiCall = new self('GET', 'ProductUsage/SumOfValue');

        return $apiCall;
    }

}
