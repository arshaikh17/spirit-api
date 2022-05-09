<?php

namespace Edcoms\SpiritApiBundle\Helper;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Call\ActivityCall;
use Edcoms\SpiritApiBundle\Caller\ApiCaller;
use Edcoms\SpiritApiBundle\Helper\AbstractHelper;
use Edcoms\SpiritApiBundle\Model\Activity;

/**
 * Service used to make API calls.
 * This particular service is dedicated to make calls relating to Activities.
 *
 * @see  http://apidocs.educationcompany.co.uk/#activities
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class ActivityHelper extends AbstractHelper
{
    public function createActivity(Activity $activity)
    {
        $apiCall = ActivityCall::Create($activity);
        $response = $this->makeCall($apiCall);

        return $response;
    }

    /**
     * {inheritdoc}
     */
    public function classToMap(): string
    {
        return Activity::class;
    }
}
