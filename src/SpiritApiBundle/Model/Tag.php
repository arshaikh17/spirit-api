<?php
/**
 * Created by PhpStorm.
 * User: dimitris
 */

namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Model\AbstractModel;

class Tag extends AbstractModel{

    /**
     * @Mapping(name="TagName", type="string")
     */
    public $tagName;

    /**
     * @Mapping(name="TagId", type="string")
     */
    public $tagId;

    /**
     * @Mapping(name="ContainerId", type="string")
     */
    public $containerId;

    /**
     * @Mapping(name="ContainerName", type="string")
     */
    public $containerName;
}
