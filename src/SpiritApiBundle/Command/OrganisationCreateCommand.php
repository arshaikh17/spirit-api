<?php

namespace Edcoms\SpiritApiBundle\Command;

use Edcoms\SpiritApiBundle\Helper\OrganisationHelper;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class OrganisationCreateCommand extends ContainerAwareCommand
{
    /**
     * @var  OrganisationHelper
     */
    protected $organisationHelper;

    /**
     * @param  OrganisationHelper  $organisationHelper  The organisation helper service.
     */
    public function __construct(OrganisationHelper $organisationHelper)
    {
        $this->organisationHelper = $organisationHelper;

        parent::__construct();
    }

    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('spirit:organisation:create')
            ->setDescription('Makes a request to the SPIRIT API service to fetch organisation data and display it in the console.')
            ->addOption('id', null, InputOption::VALUE_OPTIONAL, 'ID of the organisation to retrieve.')
            ->addOption('postcode', null, InputOption::VALUE_OPTIONAL, 'Postcode (or part) of organisations to retrieve.')
            ->addOption('includeUserDefinedFields', null, InputOption::VALUE_OPTIONAL, 'Include userDefinedFields in call to spirit API endpoint - true|false (default).')
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // get the options.
        $id = $input->getOption('id');
        $postcode = $input->getOption('postcode');
        $includeUserDefinedFields = $input->getOption('includeUserDefinedFields');

        if ($id === null && $postcode === null) {
            throw new \InvalidArgumentException('Must have either \'id\' or \'postcode\' option set.');
        }

        $this->organisationHelper->setThrowExceptionOnError(false);
        $organisation = null;

        if ($postcode === null) {
            $organisation = $this->organisationHelper->getById($id, $includeUserDefinedFields);
        } else {
            $organisation = $this->organisationHelper->searchByPostcode($postcode);
        }

        if ($organisation instanceof BadApiResponse) {
            $output->writeLn('Could not find organisation: ' . $organisation->getMessage());
            return;
        }

        $output->writeLn(json_encode($organisation, JSON_PRETTY_PRINT));
    }
}
