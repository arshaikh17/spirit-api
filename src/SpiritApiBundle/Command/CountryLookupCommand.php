<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2017-09-25 11:04:17
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:25:59
 */
namespace Edcoms\SpiritApiBundle\Command;

use Edcoms\SpiritApiBundle\Helper\CountryHelper;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CountryLookupCommand extends ContainerAwareCommand
{
    /**
     * @var  CountryHelper
     */
    protected $countryHelper;

    /**
     * @param  CountryHelper  $countryHelper  The country helper service.
     */
    public function __construct(CountryHelper $countryHelper)
    {
        $this->countryHelper = $countryHelper;

        parent::__construct();
    }

    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('spirit:lookup:country')
            ->setDescription('Makes a request to the SPIRIT API service to fetch all countries and displays them in the console.')
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->countryHelper->setThrowExceptionOnError(false);
        $countries = $this->countryHelper->getAll();

        if ($countries instanceof BadApiResponse) {
            $output->writeLn('Could not fetch countries: ' . $countries->getMessage());
            return;
        }

        $output->writeLn(json_encode($countries, JSON_PRETTY_PRINT));
    }
}
