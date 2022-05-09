<?php

namespace Edcoms\SpiritApiBundle\Tests\Normalizer;

use Edcoms\SpiritApiBundle\Normalizer\ModelNormalizer;
use Edcoms\SpiritApiBundle\Model\Organisation;
use Edcoms\SpiritApiBundle\Model\WebAccount;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Unit test for the ModelNormalizer class.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class ModelNormalizerTest extends WebTestCase
{
    protected $container;

    protected function setUp()
    {
        $client = static::createClient();
        $this->container = self::$kernel->getContainer();
    }

    public function testNormalizer()
    {
        $normalizer = new ModelNormalizer($this->container->get('annotation_reader'), [
            'Edcoms\SpiritApiBundle\Model\Organisation' => [
                'type' => [
                    'id' => '00000000-0000-0000-0000-000000000001'
                ]
            ],
            'Edcoms\SpiritApiBundle\Model\Person' => [
                'jobType' => [
                    'id' => '00000000-0000-0000-0000-000000000042'
                ]
            ],
            'Edcoms\SpiritApiBundle\Model\WebAccount' => [
                'userType' => [
                    'id' => 9
                ],
                'type' => [
                    'id' => 299
                ]
            ]
        ]);

        $organisation = new Organisation();
        $normalizedOrganisation = $normalizer->normalizeModel($organisation);
        $this->assertEquals('00000000-0000-0000-0000-000000000001', $normalizedOrganisation->getType()->getId());

        $webAccount = new WebAccount();
        $normalizedWebAccount = $normalizer->normalizeModel($webAccount);
        $this->assertEquals(299, $normalizedWebAccount->getType()->getId());
        $this->assertEquals('00000000-0000-0000-0000-000000000042', $normalizedWebAccount->getPerson()->getJobType()->getId());
        $this->assertEquals('00000000-0000-0000-0000-000000000001', $normalizedWebAccount->getOrganisation()->getType()->getId());
    }
}
