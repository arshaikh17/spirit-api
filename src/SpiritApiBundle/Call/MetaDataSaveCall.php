<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2018-01-03 12:35:09
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-11 14:28:11
 */
namespace Edcoms\SpiritApiBundle\Call;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Model\MetaData;

/**
 * A object containing the necessary data to make API endpoint call to create a new WebAccount.
 *
 * @see  http://apidocs.educationcompany.co.uk/#save
 */
class MetaDataSaveCall extends ApiCall
{
    /**
     * Creates an ApiCall object, so that it can be used to call the SPIRIT service to create a WebAccount.
     *
     * @param   WebAccount  $webAccount  The model object to grab the data from to send to SPIRIT.
     *
     * @return  self                     The created ApiCall object.
     */
    public static function save(MetaData $metaData): self
    {

        $data = [
            'objectId' => $metaData->getObjectId(),
            'objectPrimaryKey' => $metaData->getObjectPrimaryKey(),
            'MetaDatas' => array()
        ];

        //add metadata fieldnames and values from array.        
        if (is_array($metaDataItems = $metaData->getMetaDatas())) {                    
            foreach($metaDataItems as $index => $item) {
                $data['MetaDatas'][] = $item;
            }            
        }

        $apiCall = new self('POST', '/MetaDatas', $data);

        return $apiCall;
    }
}
