<?php

namespace Edcoms\SpiritApiBundle\Command;

use Edcoms\SpiritApiBundle\Helper\WebAccountHelper;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Edcoms\SpiritApiBundle\Model\WebAccount;

/**
 * Command to create a WebAccount object from the SPIRIT API service.
 *
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class WebAccountCreateCommand extends ContainerAwareCommand
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
            ->setName('spirit:web_account:create')
            ->setDescription('Makes a request to the SPIRIT API service to create a web account and display it in the console.')
            ->addOption('json-payload', null, InputOption::VALUE_OPTIONAL, 'JSON payload of WebAccount data to create the WebAccount model.')
            ->addOption('web-account-model', null, InputOption::VALUE_OPTIONAL, 'WebAccountModel containing data to update on spirit - overrides json-payload option.')
            ->addOption('persist', null, InputOption::VALUE_REQUIRED, 'Persist the SpiritProduct if successfully created - true|false.')             
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // get the option.
        $json_payload = $input->getOption('json-payload');
        $webAccountModel = $input->getOption('web-account-model');
        $persist = $input->getOption('persist');

        if ($json_payload === null && $webAccountModel === null) {
            throw new \InvalidArgumentException('Must have either \'json-payload\' or \'web-account-model \' option set.');
        } elseif ($json_payload !== null && $webAccountModel !== null) {
            $output->writeLn('<info>webAccountModel will be used for create - it overrides json-payload option.</info>');
            $json_payload = null;
        } elseif ($json_payload !== null && $webAccountModel === null) {
            //map json_payload to WebAccount Model.
            $mapper = $this->getContainer()->get('spirit_api.model_mapper');
            $webAccountModel = $mapper->mapFromJson($json_payload, 'Edcoms\SpiritApiBundle\Model\WebAccount'); 
            $json_payload = null;        
        }

        if ($persist === null) {
            throw new \InvalidArgumentException('Must have the \'persist\' option set.');
        }    

        $this->webAccountHelper->setThrowExceptionOnError(false);
        $webAccount = $this->webAccountHelper->createAccount($webAccountModel);

        if ($webAccount instanceof BadApiResponse) {
            $output->writeLn('<error>Could not create the web account: ' . $webAccount->getMessage() . '</error>');
            return;
        }

        $output->writeLn(json_encode($webAccount, JSON_PRETTY_PRINT));
        $output->writeLn('<info>Web Account create command is done.</info>');
    }
}
