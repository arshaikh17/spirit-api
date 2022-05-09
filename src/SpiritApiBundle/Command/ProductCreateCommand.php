<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2017-09-26 16:40:58
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:26:12
 */
namespace Edcoms\SpiritApiBundle\Command;

use Edcoms\SpiritApiBundle\Helper\ProductHelper;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Edcoms\SpiritApiBundle\Model\Product;
use Edcoms\SpiritApiBundle\Entity\SpiritProduct;
use Doctrine\ORM\EntityManager;

class ProductCreateCommand extends ContainerAwareCommand
{
    /**
     * @var  OrganisationHelper
     */
    protected $productHelper;

    /**
     * @param  ProductHelper  $productHelper  The product helper service.
     */
    public function __construct(ProductHelper $productHelper)
    {
        $this->productHelper = $productHelper;

        parent::__construct();
    }

    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('spirit:product:create')
            ->setDescription('Makes a request to the SPIRIT API service to create a product and display the result in the console.')
            ->addOption('json_payload', null, InputOption::VALUE_REQUIRED, 'JSON payload of Product to create.')
            ->addOption('persist', null, InputOption::VALUE_REQUIRED, 'Persist the SpiritProduct if successfully created - true|false.')            
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    
        // get the options.
        $payload = $input->getOption('json_payload');
        $persist = $input->getOption('persist');

        if ($payload === null) {
            throw new \InvalidArgumentException('Must have \'json_payload\' option set.');
        }

        if ($persist === null) {
            throw new \InvalidArgumentException('Must have \'persist\' option set.');
        }        

        //map json_payload to Product Model.
        $mapper = $this->getContainer()->get('spirit_api.model_mapper');
        $productModel = $mapper->mapFromJson($payload, 'Edcoms\SpiritApiBundle\Model\Product');

        $this->productHelper->setThrowExceptionOnError(false);
        
        $createdProductModel = null;
        $createdProductModel = $this->productHelper->createProduct($productModel);

        if ($createdProductModel instanceof BadApiResponse) {
            $output->writeLn('Could not create product: ' . $createdProductModel->getMessage());
            return;
        }

        if ($persist) {
            $spiritSynchronizer = $this->getContainer()->get('spirit_api.synchronizer');
            $spiritProduct = $spiritSynchronizer->cacheModel($createdProductModel); //persists entity.
        }

        $output->writeLn(json_encode($createdProductModel, JSON_PRETTY_PRINT));
    }
}
