<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2017-09-26 14:33:49
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:26:33
 */
namespace Edcoms\SpiritApiBundle\Command;

use Edcoms\SpiritApiBundle\Helper\ProductUsageTransactionTypeHelper;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProductUsageTransactionTypeLookupCommand extends ContainerAwareCommand
{
    /**
     * @var  OrganisationHelper
     */
    protected $productUsageTransactionTypeHelper;

    /**
     * @param  ProductUsageTransactionTypeHelper  $productUsageTransactionTypeHelper  The product usage transaction type helper service.
     */
    public function __construct(ProductUsageTransactionTypeHelper $productUsageTransactionTypeHelper)
    {
        $this->productUsageTransactionTypeHelper = $productUsageTransactionTypeHelper;

        parent::__construct();
    }

    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('spirit:lookup:productusagetransactiontypes')
            ->setDescription('Makes a request to the SPIRIT API service to fetch all product usage transaction types data and display it in the console.')
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->productUsageTransactionTypeHelper->setThrowExceptionOnError(false);
        $transactionTypes = null;

        $transactionTypes = $this->productUsageTransactionTypeHelper->getAll();

        if ($transactionTypes instanceof BadApiResponse) {
            $output->writeLn('Could not find product usage transaction types: ' . $transactionTypes->getMessage());
            return;
        }

        $output->writeLn(json_encode($transactionTypes, JSON_PRETTY_PRINT));
    }
}
