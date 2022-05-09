<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2018-01-03 14:14:45
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:28:50
 */

namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Model\AbstractModel;

/**
 * Object representation of a MetaDataItem, which matches:
 * - single array item in metaDatas property of MetaData save endpoint payload.
 * - single array item in the metaDataItems property of the MetaData load endpoint payload.
 *
 *  Saving MetaData: only require fieldName and value properties.
 *  Loading MetaData: all fields populated from spirit response.
 * 
 */
class MetaDataItem extends AbstractModel
{

    /**
     * @Mapping(name="FieldName", type="string")
     */
    public $fieldName;

    /**
     * @Mapping(name="Value", type="string")
     */
    public $value;

    /**
     * @Mapping(name="DataType", type="string")
     */
    public $dataType;

    /**
     * @Mapping(name="Label", type="string")
     */
    public $label;

    /**
     * @Mapping(name="FieldId", type="string")
     */
    public $fieldId;

    /**
     * @Mapping(name="ContainerId", type="string")
     */
    public $containerId;                
    

}
