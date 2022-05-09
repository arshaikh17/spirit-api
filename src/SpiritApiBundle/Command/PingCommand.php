<?php

namespace Edcoms\SpiritApiBundle\Command;

use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Caller\ApiCallerInterface;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Submits a ping call to the SPIRIT API service and returns the received URL if successful.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class PingCommand extends ContainerAwareCommand
{
    /**
     * @var  ApiCallerInterface
     */
    protected $apiCaller;

    /**
     * @var  string
     */
    protected $url;

    /**
     * @param  ApiCallerInterface  $apiCaller  The API caller service.
     * @param  string              $url        The SPIRIT service URL.
     */
    public function __construct(ApiCallerInterface $apiCaller, string $url)
    {
        $this->apiCaller = $apiCaller;
        $this->url = $url;

        parent::__construct();
    }

    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('spirit:ping')
            ->setDescription('Makes a ping request to the SPIRIT API service.')
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $apiCall = new ApiCall('GET', "{$this->url}/API/url");
        $apiCall->setUriAbsolute(true);
        $response = $this->apiCaller->makeCall($apiCall);

        if ($response->getIsError()) {
            $output->writeLn('An error occured whilst making a \'ping\' call to the SPIRIT service.');
            return;
        }

        $url = $response->getData();

        $output->writeLn("Ping successful. Your API URL is: {$url}" . PHP_EOL);

        if (strpos($url, $this->url) !== false) {
            $output->writeLn('Your API version number is: '.str_replace($this->url, '', $url).PHP_EOL);
        }
    }
}
