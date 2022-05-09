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
 * Command to update a WebAccount object from the SPIRIT API service.
 *
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class WebAccountUpdateCommand extends ContainerAwareCommand
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
            ->setName('spirit:web_account:update')
            ->setDescription('Makes a request to the SPIRIT API service to update a web account data and display it in the console.')
            ->addOption('json-payload', null, InputOption::VALUE_OPTIONAL, 'JSON payload of WebAccount data to update.')
            ->addOption('web-account-model', null, InputOption::VALUE_OPTIONAL, 'WebAccountModel containing data to update on spirit - overrides json-payload option.')
            ->addOption('id', null, InputOption::VALUE_OPTIONAL, 'Spirit ID of the Web Account Model to update. Required with \' json-payload\' to support request.')
            ->addOption('persist', null, InputOption::VALUE_REQUIRED, 'Persist the SpiritProduct if successfully update - true|false.')             
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // get the option.
        $json_payload = $input->getOption('json-payload');
        $id = $input->getOption('id');
        $webAccountModel = $input->getOption('web-account-model');
        $persist = $input->getOption('persist');

        if ($json_payload === null && $webAccountModel === null) {
            throw new \InvalidArgumentException('Must have either \'json-payload\' or \'web-account-model \' option set.');
        } elseif ($json_payload !== null && $webAccountModel !== null) {
            $output->writeLn('<info>webAccountModel will be used for update - it overrides json-payload option.</info>');
            $json_payload = null;
        } elseif ($json_payload !== null && $id === null && $webAccountModel === null) {
            throw new \InvalidArgumentException('Must have \'id\' option set when submitting \'json-payload \'.');
        } elseif ($json_payload !== null && $id !== null && $webAccountModel === null) {
            //map json_payload to WebAccount Model.
            $mapper = $this->getContainer()->get('spirit_api.model_mapper');
            $webAccountModel = $mapper->mapFromJson($json_payload, 'Edcoms\SpiritApiBundle\Model\WebAccount');  
            $webAccountModel->setId($id);
            $json_payload = null;        
            $id = null;  
        }

        if ($persist === null) {
            throw new \InvalidArgumentException('Must have the \'persist\' option set.');
        }    

        $this->webAccountHelper->setThrowExceptionOnError(false);
        $webAccount = $this->webAccountHelper->updateAccount($webAccountModel);

        if ($webAccount instanceof BadApiResponse) {
            $output->writeLn('<error>Could not update the web account: ' . $webAccount->getMessage() . '</error>');
            return;
        }

        $output->writeLn(json_encode($webAccount, JSON_PRETTY_PRINT));
        $output->writeLn('<info>Web Account update command is done.</info>');
    }
}
