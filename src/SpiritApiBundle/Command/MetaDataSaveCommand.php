<?php

/**
 * @Author: Daniel Forer
 * @Date:   2018-01-09 18:12:51
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-01-11 14:42:30
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

class MetaDataSaveCommand extends ContainerAwareCommand
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
            ->setName('spirit:metadata:save')
            ->setDescription('Makes a request to the SPIRIT API service to save metadata associated to an objectID and objectPrimaryKey.')
            ->addOption('objectID', null, InputOption::VALUE_REQUIRED, 'Object Type ID - see MetaDataSupportedObjectInterface for IDs.')
            ->addOption('objectPrimaryKey', null, InputOption::VALUE_REQUIRED, 'Spirit Id of the object to load metadata.')
            ->addOption('fieldName', null, InputOption::VALUE_REQUIRED, 'Field Name - name of field in metadataitems to update/save.')
            ->addOption('fieldValue', null, InputOption::VALUE_REQUIRED, 'Field Value - value of the fieldName for updating.')
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
        $fieldName = $input->getOption('fieldName');
        $fieldValue = $input->getOption('fieldValue');        

        //redundent checks as opions are required, but to ensure this in case options are made optional in configure().
        if ($objectID === null) {
            throw new \InvalidArgumentException('Must have \'objectID\' option set.');
        }

        if ($objectPrimaryKey === null) {
            throw new \InvalidArgumentException('Must have \'objectPrimaryKey\' option set.');
        }

        if ($fieldName === null) {
            throw new \InvalidArgumentException('Must have \'fieldName\' option set.');
        }

        if ($fieldValue === null) {
            throw new \InvalidArgumentException('Must have \'fieldValue\' option set.');
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
            $output->writeLn('Could not load metadata for updating: ' . $metaDataModel->getMessage());
            return;
        }  

        $metaDataModel->updateMetaDataItem($fieldName, $fieldValue); //update the values in the model.

        $response = $this->metaDataHelper->saveMetaData($metaDataModel);        

        $output->writeLn('MetaData Saved: ' . $response['result'] . ' ' . json_encode($response['data'], JSON_PRETTY_PRINT));         
        return;
    }
}
