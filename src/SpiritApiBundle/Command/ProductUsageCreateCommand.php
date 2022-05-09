<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2017-09-26 14:33:49
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:26:19
 */
namespace Edcoms\SpiritApiBundle\Command;

use Edcoms\SpiritApiBundle\Helper\ProductUsageHelper;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Edcoms\SpiritApiBundle\Model\ProductUsage;

class ProductUsageCreateCommand extends ContainerAwareCommand
{
    /**
     * @var  OrganisationHelper
     */
    protected $productUsageHelper;

    /**
     * @param  ProductUsageHelper  $productUsageHelper  The product helper service.
     */
    public function __construct(ProductUsageHelper $productUsageHelper)
    {
        $this->productUsageHelper = $productUsageHelper;

        parent::__construct();
    }

    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('spirit:productusage:create')
            ->setDescription('Makes a request to the SPIRIT API service to create a productusage transaction and display the result in the console.')
            ->addOption('json_payload', null, InputOption::VALUE_REQUIRED, 'JSON payload of ProductUsage to create.')
            ->addOption('persist', null, InputOption::VALUE_REQUIRED, 'Persist the SpiritProductUsage if successfully created - true|false.') 
            ->addOption('full_hydration', false, InputOption::VALUE_OPTIONAL, 'If true, full object hydration will happen following json decode to ProductUsage Model.')            
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // get the options.
        $payload = $input->getOption('json_payload');
        $fullHydration = $input->getOption('full_hydration');
        $persist = $input->getOption('persist');

        if ($payload === null) {
            throw new \InvalidArgumentException('Must have \'json_payload\' option set.');
        }

        //map json_payload to Product Model.
        $mapper = $this->getContainer()->get('spirit_api.model_mapper');
        $productUsageModel = $mapper->mapFromJson($payload, 'Edcoms\SpiritApiBundle\Model\ProductUsage');

        if (empty($productUsageModel))
        {
            $output->writeLn('Could not create product usage model - missing data in json.');
            return;   
        }


        if ($fullHydration) {
            //to add later if need to get full object hydration for 
        }      

        $this->productUsageHelper->setThrowExceptionOnError(false);
        $createdProductUsage = null;

        $createdProductUsage = $this->productUsageHelper->createProductUsage($productUsageModel);

        if ($createdProductUsage instanceof BadApiResponse) {
            $output->writeLn('Could not create product usage transaction: ' . $createdProductUsage->getMessage());
            return;
        }


        if ($persist) {
            $spiritSynchronizer = $this->getContainer()->get('spirit_api.synchronizer');
            $spiritProductUsage = $spiritSynchronizer->cacheModel($productUsageModel); //persists entity.
        }

        $output->writeLn(json_encode($createdProductUsage, JSON_PRETTY_PRINT));

    }
}
