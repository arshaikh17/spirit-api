<?php

namespace Edcoms\SpiritApiBundle\Model\Interfaces;

use Edcoms\SpiritApiBundle\Model\WebAccount;

/**
 * Contains all necessary methods required to compose data to send to SPIRIT,
 * as part of a standard user registration service.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
interface SpiritRegistrationInterface
{
    /**
     * Creates a WebAccount model, populated with values and other associating models.
     * This will be the necessary data which will be used to create a WebAccount object in the SPIRIT service.
     *
     * @return  WebAccount  The created WebAccount model instance.
     */
    public function toWebAccountModel(): WebAccount;
}
