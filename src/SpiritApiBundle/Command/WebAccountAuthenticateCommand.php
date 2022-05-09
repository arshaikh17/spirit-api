<?php

/**
 * @Author: Daniel Forer
 * @Date:   2018-03-02 11:17:46
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-03-02 13:47:40
 */

namespace Edcoms\SpiritApiBundle\Command;

use Edcoms\SpiritApiBundle\Helper\WebAccountHelper;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Edcoms\SpiritApiBundle\Model\WebAccount;

/**
 * Command to authenticate a WebAccount object from the SPIRIT API service.
 *
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class WebAccountAuthenticateCommand extends ContainerAwareCommand
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
            ->setName('spirit:web_account:authenticate')
            ->setDescription('Makes a request to the SPIRIT API service to authenticate a web account and display result it in the console.')
            ->addOption('webAccountUsername', null, InputOption::VALUE_OPTIONAL, 'WebAccount Username from a WebAccount to authenticate against spirit.')
            ->addOption('webAccountPassword', null, InputOption::VALUE_OPTIONAL, 'WebAccount Password from a WebAccount to authenticate against spirit.')
            ->addOption('webAccountUserTypeId', null, InputOption::VALUE_OPTIONAL, 'WebAccount User Type ID from a WebAccount to authenticate against spirit.')                        
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // get the option.
        $username = $input->getOption('webAccountUsername');
		$password = $input->getOption('webAccountPassword');        
		$webAccountUserTypeId = $input->getOption('webAccountUserTypeId');        

        if ($username === null || $password === null || $webAccountUserTypeId === null) {
            throw new \InvalidArgumentException('Must have \'webAccountUsername\', \'webAccountPassword\' and \'webAccountUserTypeId\' options set.');
        }

        $this->webAccountHelper->setThrowExceptionOnError(false);
        $response = $this->webAccountHelper->authenticateAccount($username, $password, $webAccountUserTypeId);

        if ($response instanceof BadApiResponse) {
            $output->writeLn('<error>Could not authenticate the web account: ' . $response->getMessage() . '</error>');
            return;
        }        

        $output->writeLn(json_encode($response, JSON_PRETTY_PRINT));
        $output->writeLn('<info>Web Account authenticate command is done.</info>');
    }
}
