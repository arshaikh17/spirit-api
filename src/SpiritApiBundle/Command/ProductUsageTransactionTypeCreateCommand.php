<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2017-09-26 14:33:49
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-04 17:26:26
 */
namespace Edcoms\SpiritApiBundle\Command;

use Edcoms\SpiritApiBundle\Helper\ProductUsageTransactionTypeHelper;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Edcoms\SpiritApiBundle\Model\ProductUsageTransactionType;
use Edcoms\SpiritApiBundle\Mapper\ModelMapper;

class ProductUsageTransactionTypeCreateCommand extends ContainerAwareCommand
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
            ->setName('spirit:productusagetransactiontype:create')
            ->setDescription('Makes a request to the SPIRIT API service to create a product usage transaction types and display response in the console.')
            ->addOption('json_payload', null, InputOption::VALUE_REQUIRED, 'JSON payload of Product Usage Transaction Type to create.')            
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // get the options.
        $payload = $input->getOption('json_payload');

        if ($payload === null) {
            throw new \InvalidArgumentException('Must have \'json_payload\' option set.');
        }

        //map json_payload to Product Model.
        $mapper = $this->getContainer()->get('spirit_api.model_mapper');
        $productUsageTransactionTypeModel = $mapper->mapFromJson($payload, 'Edcoms\SpiritApiBundle\Model\ProductUsageTransactionType');
     

        $this->productUsageTransactionTypeHelper->setThrowExceptionOnError(false);
        $createdTransactionType = null;

        $createdTransactionType = $this->productUsageTransactionTypeHelper->createProductUsageTransactionType($productUsageTransactionTypeModel);

        if ($createdTransactionType instanceof BadApiResponse) {
            $output->writeLn('Could not create product usage transaction type: ' . $createdTransactionType->getMessage());
            return;
        }

        $output->writeLn(json_encode($createdTransactionType, JSON_PRETTY_PRINT));
    }
}
