<?php

/**
 * @Author: Daniel Forer
 * @Date:   2018-03-02 15:42:22
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-03-02 16:08:39
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
 * Command to update password of a WebAccount object from the SPIRIT API service.
 *
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class WebAccountUpdatePasswordCommand extends ContainerAwareCommand
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
            ->setName('spirit:web_account:update_password')
            ->setDescription('Makes a request to the SPIRIT API service to update the password of a web account and display result it in the console.')
            ->addOption('webAccountID', null, InputOption::VALUE_OPTIONAL, 'WebAccount ID to update on spirit.')
            ->addOption('newPassword', null, InputOption::VALUE_OPTIONAL, 'New Password to update web account against spirit.')                                  
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // get the option.
        $webAccountID = $input->getOption('webAccountID');
		$newPassword = $input->getOption('newPassword');        

        if ($webAccountID === null || $newPassword === null) {
            throw new \InvalidArgumentException('Must have \'webAccountID\' and \'newPassword\' options set.');
        }

        $this->webAccountHelper->setThrowExceptionOnError(false);
        $response = $this->webAccountHelper->updatePassword($webAccountID, $newPassword);

        if ($response instanceof BadApiResponse) {
            $output->writeLn('<error>Could not update password of the web account: ' . $response->getMessage() . '</error>');
            return;
        }        

        $output->writeLn(json_encode($response, JSON_PRETTY_PRINT));
        $output->writeLn('<info>Web Account update password command is done.</info>');
    }
}
