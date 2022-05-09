<?php

/**
 * @Author: Daniel Forer
 * @Date:   2018-01-09 18:12:51
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-09 18:58:39
 */

namespace Edcoms\SpiritApiBundle\Command;

use Edcoms\SpiritApiBundle\Helper\MetaDataHelper;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

//currently used with metadata
use Edcoms\SpiritApiBundle\Entity\SpiritOrganisation;
use Edcoms\SpiritApiBundle\Entity\SpiritUser;
use Edcoms\SpiritApiBundle\Entity\SpiritProductUsage;

class MetaDataLoadCommand extends ContainerAwareCommand
{
    /**
     * @var  CountryHelper
     */
    protected $metaDataHelper;

    /**
     * @param  MetaDataHelper  $metaDataHelper  The metadata helper service.
     */
    public function __construct(MetaDataHelper $metaDataHelper)
    {
        $this->metaDataHelper = $metaDataHelper;

        parent::__construct();
    }

    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('spirit:metadata:load')
            ->setDescription('Makes a request to the SPIRIT API service to fetch a metadata for an objectID and objectPrimaryKey.')
            ->addOption('objectID', null, InputOption::VALUE_REQUIRED, 'Object Type ID - see MetaDataSupportedObjectInterface for IDs.')
            ->addOption('objectPrimaryKey', null, InputOption::VALUE_REQUIRED, 'Spirit Id of the object to load metadata.')             
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //init input options.
        $objectID = $input->getOption('objectID');
        $objectPrimaryKey = $input->getOption('objectPrimaryKey');

        //redundent checks as opions are required, but to ensure this in case options are made optional in configure().
        if ($objectID === null) {
            throw new \InvalidArgumentException('Must have \'objectID\' option set.');
        }

        if ($objectPrimaryKey === null) {
            throw new \InvalidArgumentException('Must have \'objectPrimaryKey\' option set.');
        }

        //the above options are used to fetch the appropriate object from our DB first - to mimic the input to the MetaDataHelper load function.
        
        $em = $this->getContainer()->get('doctrine')->getManager();  
        $object = null;                  

        switch ($objectID) {
            case 1: //organisation
                $object = $em->getRepository(SpiritOrganisation::class)->findOneBy(array('spiritId' => $objectPrimaryKey));
                break;
            case 15: //webaccount (spirituser)
                $object = $em->getRepository(SpiritUser::class)->findOneBy(array('spiritId' => $objectPrimaryKey));
                break;
            case 36: //productusage
                $object = $em->getRepository(SpiritProductUsage::class)->findOneBy(array('spiritId' => $objectPrimaryKey));
                break;
        }

        if ($object === null) {
            throw new \InvalidArgumentException('No object found for \'objectID\' and \'objectPrimaryKey\' options.');
        }

        $metaDataModel = $this->metaDataHelper->loadMetaData($object);

        if ($metaDataModel instanceof BadApiResponse) {
            $output->writeLn('Could not load metadata: ' . $metaDataModel->getMessage());
            return;
        }  

        $output->writeLn(json_encode($metaDataModel, JSON_PRETTY_PRINT));   

        return;   

        
    }
}
