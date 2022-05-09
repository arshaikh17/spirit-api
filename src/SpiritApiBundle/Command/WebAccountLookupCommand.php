<?php

namespace Edcoms\SpiritApiBundle\Command;

use Edcoms\SpiritApiBundle\Helper\WebAccountHelper;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to retrieve a WebAccount object from the SPIRIT API service.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class WebAccountLookupCommand extends ContainerAwareCommand
{
    /**
     * @var  WebAccountHelper
     */
    protected $webAccountHelper;

    /**
     * @param  WebAccountHelper  $webAccountHelper  The web account helper service.
     */
    public function __construct(WebAccountHelper $webAccountHelper)
    {
        $this->webAccountHelper = $webAccountHelper;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('spirit:lookup:web_account')
            ->setDescription('Makes a request to the SPIRIT API service to fetch web account data and display it in the console.')
            ->addOption('id', null, InputOption::VALUE_REQUIRED, 'ID of the web account to retrieve.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // get the option.
        $id = $input->getOption('id');

        if ($id === null) {
            throw new \InvalidArgumentException('Must have the \'id\' option set.');
        }

        $this->webAccountHelper->setThrowExceptionOnError(false);
        $webAccount = $this->webAccountHelper->getById($id);

        if ($webAccount instanceof BadApiResponse) {
            $output->writeLn('Could not find the web account: ' . $webAccount->getMessage());
            return;
        }

        $output->writeLn(json_encode($webAccount, JSON_PRETTY_PRINT));
    }
}
