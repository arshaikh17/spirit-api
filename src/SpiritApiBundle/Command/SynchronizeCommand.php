<?php

namespace Edcoms\SpiritApiBundle\Command;

use Doctrine\Common\Annotations\Reader;
use Edcoms\SpiritApiBundle\Call\ApiCall;
use Edcoms\SpiritApiBundle\Caller\ApiCallerInterface;
use Edcoms\SpiritApiBundle\Helper\SpiritApiHelpers;
use Edcoms\SpiritApiBundle\Response\BadApiResponse;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncEntity;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Synchronizes all synchronizable entities with data stored in the SPIRIT API.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class SynchronizeCommand extends ContainerAwareCommand
{
    /**
     * @var  Reader
     */
    protected $annotationReader;

    /**
     * @var  ApiCallerInterface
     */
    protected $apiCaller;

    /**
     * @var  SpiritApiHelpers
     */
    protected $spiritApiHelpers;

    /**
     * @param  Reader              $annotationReader  The annotation reader service.
     * @param  ApiCallerInterface  $apiCaller         The API caller service.
     * @param  SpiritApiHelpers    $SpiritApiHelpers  The SPIRIT helper container service.
     */
    public function __construct(Reader $annotationReader, ApiCallerInterface $apiCaller, SpiritApiHelpers $spiritApiHelpers)
    {
        $this->annotationReader = $annotationReader;
        $this->apiCaller = $apiCaller;
        $this->spiritApiHelpers = $spiritApiHelpers;

        parent::__construct();
    }

    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('spirit:sync')
            ->setDescription('Synchronizes data with SPIRIT API service data.')
            ->addOption('object-type', null, InputOption::VALUE_OPTIONAL, 'Name of the type of object to synchronize. The \'ID\' or \'RANGE\' option must be specified if this is used.')
            ->addOption('id', null, InputOption::VALUE_OPTIONAL, 'Spirit ID of the object to synchronize, overrides range option.')
            ->addOption('scope', null, InputOption::VALUE_OPTIONAL, 'ALL.')          
            ->addOption('list-objects', null, InputOption::VALUE_NONE, 'Lists all available objects types.')
            ->addOption('include-user-defined-fields', null, InputOption::VALUE_OPTIONAL, 'Include userDefinedFields in call to spirit API endpoint, if available - true|false (default).')
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('list-objects')) {
            $this->listAvailableObjects($output);
            return;
        }

        $objectId = $input->getOption('id');
        $objectScope = $input->getOption('scope');
        $objectType = $input->getOption('object-type') ?: '';
        $includeUserDefinedFields = $input->getOption('include-user-defined-fields') === null ? false : true;

        if ($objectScope !== null && $objectId !== null) {
            $output->writeLn("Synchronizing id as it overrides scope option for the objects..." . PHP_EOL);
            $objectScope = null;
        }

        if ($objectScope !== null && $objectId === null) {
            $output->writeLn("Synchronizing scope option for the objects..." . PHP_EOL);
        }

        $helper = $this->spiritApiHelpers->getHelper($objectType);

        if (null === $helper) {
            throw new \Exception("Cannot synchronize models for the object named '$objectType'.");
        }

        $modelClass = $helper->classToMap();
        $output->writeLn("Synchronizing '$modelClass' objects..." . PHP_EOL);

        $reflectionClass = new \ReflectionClass($modelClass);
        $syncEntityAnnotation = $this->annotationReader->getClassAnnotation(
            $reflectionClass,
            SyncEntity::class
        );

        if (null === $syncEntityAnnotation) {
            throw new \Exception(
                "Cannot synchronize '$modelClass' objects as it does not implement the annotation '" . SyncEntity::class . '\'.'
            );
        }

        if ($objectScope !== null) {
            $em = $this->getContainer()->get('doctrine')->getEntityManager();
            $offset = 0;
            $batch = 100;
            while(count($localObjects = $em->getRepository($syncEntityAnnotation->getEntity())->findBy(array(), array('id' => 'ASC'), $batch, $offset))>0){
                $output->writeLn(count($localObjects) . " objects to synchronize..." . PHP_EOL);

                foreach($localObjects as $object) {
                    try{
                        $this->synchronizeObject($helper, $includeUserDefinedFields, $object->getSpiritId(), $output);
                    }catch (\Exception $e){
                        $output->writeln('error: '. $object->getId().' '. $e->getMessage());
                    }

                }
                $offset += $batch;
                $em->clear();
            }


        } else {
            $this->synchronizeObject($helper, $includeUserDefinedFields, $objectId, $output);
        }
        
        $output->writeLn('Done.' . PHP_EOL);
    }

    /**
     * Lists all available helper services.
     *
     * @param  OutputInterface  $output  The output interface instance.
     */
    protected function listAvailableObjects(OutputInterface $output)
    {
        $output->writeLn(PHP_EOL . 'Available objects:');

        foreach ($this->spiritApiHelpers->getAvailableHelperNames() as $helperName) {
            $output->writeLn("  {$helperName}");
        }

        $output->writeLn('');
    }

    protected function synchronizeObject($helper, $includeUserDefinedFields, $objectId, $output) {
        if ($includeUserDefinedFields) {
            $model = $helper->getById($objectId,$includeUserDefinedFields);
        } else {
            $model = $helper->getById($objectId);
        }

        if ($model instanceof BadApiResponse) {
            $output->writeLn("Could not synchronize '$objectId' due to error from Spirit API: " . $model->getMessage());
            return;
        }

        $spiritSynchronizer = $this->getContainer()->get('spirit_api.synchronizer');
        if($model){
            $spiritModelResponse = $spiritSynchronizer->cacheModel($model); //persists entity.
            $output->writeLn("Synchronized '$objectId'.");
        }else{
            $output->writeLn("Not Synchronized '$objectId'.");
        }
    }
}
