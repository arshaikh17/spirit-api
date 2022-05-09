<?php

namespace Edcoms\SpiritApiBundle\Helper;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Caller\ApiCaller;
use Edcoms\SpiritApiBundle\Helper\AbstractHelper;
use Edcoms\SpiritApiBundle\Model\OrganisationType;

/**
 * Service used to make API calls.
 * This particular service is dedicated to make calls relating to Organisation Types.
 *
 * @see  http://apidocs.educationcompany.co.uk/#organisations
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class OrganisationTypeHelper extends AbstractHelper
{
    private $orgTypes = null;

    public function getById($id){
        if($this->orgTypes === null){
            $orgTypes = $this->getAll();
            $this->orgTypes = [];
            if($orgTypes && is_array($orgTypes) && count($orgTypes)){
                foreach ($orgTypes as $orgType){
                    $this->orgTypes[$orgType->id] = $orgType;
                }
            }
        }
        return isset($this->orgTypes[$id]) ? $this->orgTypes[$id] : null;
    }

    public function getAll()
    {

        $apiCallData = [];

        $apiCall = new ApiCall(
            'GET',
            "/OrganisationTypes",
            $apiCallData
        );

        $organisationTypes = $this->makeCallAndMapResponse($apiCall);

        return $organisationTypes;
    }


    /**
     * {inheritdoc}
     */
    public function classToMap(): string
    {
        return OrganisationType::class;
    }

    /**
     * @return  int  Get search limit.
     */
    private function getSearchLimit(): int
    {
        return intval($this->getOption('search_limit'));
    }
}
