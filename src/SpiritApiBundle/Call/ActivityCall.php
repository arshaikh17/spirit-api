<?php

namespace Edcoms\SpiritApiBundle\Call;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Model\Activity;

/**
 * A object containing the necessary data to make API endpoint call to create a new Activity.
 *
 * @see  http://apidocs.educationcompany.co.uk/#activities-api-post
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class ActivityCall extends ApiCall
{
    /**
     * An ApiCall object used to call the SPIRIT service to create/update an Activity.
     *
     * @param   Activity  $activity  The model object to grab the data from to send to SPIRIT.
     *
     * @return  self                 The returned ApiCall object.
     */
    public static function create(Activity $activity): self
    {
        $webAccount = $activity->getWebAccount();

        $data = [
            'ActivityTitle' => $activity->getTitle(),
            // 'OrgId' => $webAccount->getOrganisation()->getId(),  // TODO: clarify if needed.
            // 'PersonId' => $webAccount->getPerson()->getId(),  // TODO: clarify if needed.
            'RecordTypeId' => $activity->getRecordType()->getId(),
            'OwnerUserId' => $webAccount->getId() // TODO: clarify if needed.
        ];

        $apiCall = new self('POST', '/Activities', $data);

        return $apiCall;
    }
}
