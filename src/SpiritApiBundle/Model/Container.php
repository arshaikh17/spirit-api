<?php
/**
 * Created by PhpStorm.
 * User: dimitris
 */

namespace Edcoms\SpiritApiBundle\Model;

use Edcoms\SpiritApiBundle\Mapper\Annotation\Mapping;
use Edcoms\SpiritApiBundle\Model\AbstractModel;

class Container extends AbstractModel{

    /**
     * @Mapping(name="Id", type="string")
     */
    public $id;

    /**
     * @Mapping(name="Code", type="string")
     */
    public $code;

    /**
     * @Mapping(name="Description", type="string")
     */
    public $description;

    /**
     * @Mapping(name="ContainerName", type="string")
     */
    public $containerName;
}
