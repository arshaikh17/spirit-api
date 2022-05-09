<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2018-01-03 12:35:09
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:25:43
 */
namespace Edcoms\SpiritApiBundle\Call;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Model\ProductUsageTransactionType;

/**
 * An object containing the necessary data to make API endpoint call to create a new Product Usage Transaction Type.
 *
 * @see  http://apidocs.educationcompany.co.uk/#products-2
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class ProductUsageTransactionTypeCall extends ApiCall
{
    /**
     * Creates an ApiCall object, so that it can be used to call the SPIRIT service to create a Product Usage Transaction Type.
     *
     * @param   ProductUsageTransactionType  $productUsageTransactionType  The model object to grab the data from to send to SPIRIT.
     *
     * @return  ApiCall                                                    The created ApiCall object.
     */
    public static function create(ProductUsageTransactionType $productUsageTransactionType)
    {
        $apiCallData = [
            'Description' => $productUsageTransactionType->getDescription(),
            'Active' => $productUsageTransactionType->getActive()            
        ];

        $apiCall = new ApiCall('POST', '/ProductUsage/TransactionType', $apiCallData);

        return $apiCall;
    }

    /**
     *
     * Creates an ApiCall object, so that it can be used to call the SPIRIT service to update a Product Usage Transaction Type.
     * - can be used to update the description and active fields only.
     *
     * Ref: http://apidocs.educationcompany.co.uk/#product-usage-transaction-type-update
     * - error in ref: endpoint is /ProductUsage/TransactionType and not /ProductUsage as documented.
     *
     * @param   ProductUsageTransactionType  $productUsageTransactionType  The model object to grab the data from to send to SPIRIT.
     *
     * @return  ApiCall                                                    The created ApiCall object.
     */
    public static function update(ProductUsageTransactionType $productUsageTransactionType)
    {
        $apiCallData = [
            'Description' => $productUsageTransactionType->getDescription(),
            'Active' => $productUsageTransactionType->getActive()            
        ];

        $apiCall = new ApiCall('PUT', '/ProductUsage/TransactionType', $apiCallData);

        return $apiCall;
    }    
}
