<?php

namespace Edcoms\SpiritApiBundle\Event;

use Edcoms\SpiritApiBundle\Model\AbstractModel;
use Edcoms\SpiritApiBundle\Response\ApiResponse;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event to be dispatched at the time of a SPIRIT service call.
 *
 * @author  James Stubbs  <james.stubbs@edcoms.co.uk>
 */
class SpiritCallEvent extends Event
{
    /**
     * @var  ApiResponse
     */
    private $response;

    /**
     * @param  ApiResponse  $response  The response from a SPIRIT service call.
     */
    public function __construct(ApiResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @return  ApiResponse  The response from a SPIRIT service call.
     */
    public function getResponse(): ApiResponse
    {
        return $this->response;
    }
}
