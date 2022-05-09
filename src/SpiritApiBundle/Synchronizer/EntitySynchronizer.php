<?php

namespace Edcoms\SpiritApiBundle\Synchronizer;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManager;
use Edcoms\SpiritApiBundle\Entity\Interfaces\SpiritSynchronizableInterface;
use Edcoms\SpiritApiBundle\Model\AbstractModel;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncEntity;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncMultipleProperty;
use Edcoms\SpiritApiBundle\Synchronizer\Annotation\SyncProperty;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Grabs all synchronizable entity objects and calls the SPIRIT service making sure the data matches.
 * If not, the data is written to the entity object and persisted to the database.
 *
 * @author  James Stubbs <james.stubbs@edcoms.co.uk>
 */
class EntitySynchronizer
{
    /**
     * @var  Reader
     */
    protected $annotationReader;

    /**
     * @var  EntityManager
     */
    protected $em;

    /**
     * @var  int
     */
    protected $expiry;

    /**
     * @var  int
     */
    protected $maxAutoSync;

    /**
     * @param  EntityManager  $em                The entity manager.
     * @param  Reader         $annotationReader  The annotation reader service.
     * @param  int            $expiry            Expiry time for entity to be unsynchronized (in seconds).
     * @param  int            $maxAutoSync       Maximum number of models to synchronize at one time.
     */
    public function __construct(EntityManager $em, Reader $annotationReader, int $expiry, int $maxAutoSync)
    {
        $this->annotationReader = $annotationReader;
        $this->em = $em;
        $this->expiry = $expiry;
        $this->maxAutoSync = $maxAutoSync;
    }

    /**
     * Stores (or updates if it already exists) the entity relating to the SPIRIT ID contained in '$model'.
     *
     * @param   AbstractModel  $model  The model containing the data to create or update the SPIRIT entity from.
     *
     * @return                         The newly created, or updated, entity.
     */
    public function cacheModel(AbstractModel $model)
    {
        $spiritEntity = $this->getEntityForModel($model, true);
        $this->updateEntityFromModel($spiritEntity, $model);

        $this->em->flush();

        return $spiritEntity;
    }

    /**
     * Checks '$entity' to see if the synchronization timestamp is within the expiry time.
     *
     * @param   SpiritSynchronizableInterface  $entity  Entity to check for synchronization.
     *
     * @return  bool                                    'true' if '$entity' is synchronized.
     */
    public function entityIsSynchronized(SpiritSynchronizableInterface $entity): bool
    {
        $lastSynchronized = $entity->getLastSynchronized();

        return $lastSynchronized->diff(new \DateTime())->days <= $this->expiry;
    }

    /**
     * Filters down '$models' to a collection of synchronizable models.
     * An empty array is returned if the number of synchronizable models exceeds the maximum allowed.
     * The maximum is set via the configuration in the 'options'.
     *
     * @param   array  $models  Collection of models to filter.
     *
     * @return  array           The filtered models.
     */
    public function filterSynchronizableModels(array $models): array
    {
        $modelClassCache = [];
        $filteredModels = [];

        foreach ($models as $model) {
            $modelClass = get_class($model);

            // get the annotation to read the 'autoSync' property.
            if (!isset($modelClassCache[$modelClass])) {
                $reflectionClass = new \ReflectionClass($model);
                $syncEntityAnnotation = $this->annotationReader->getClassAnnotation(
                    $reflectionClass,
                    SyncEntity::class
                );

                // if the annotation does not exist,
                // use 'false' as the default indicating the current model is not synchronizable.
                $modelClassCache[$modelClass] = null === $syncEntityAnnotation ? false : $syncEntityAnnotation->getAutoSync();
            }

            if ($modelClassCache[$modelClass]) {
                // if we have already reached the maximum, break loop early.
                if (count($filteredModels) === $this->maxAutoSync) {
                    $filteredModels = [];
                    break;
                }

                $filteredModels[] = $model;
            }
        }

        // memory management.
        unset($modelClassCache);

        return $filteredModels;
    }

    /**
     * @return  int  Get expiry time (in days).
     */
    public function getExpiry(): int
    {
        return $this->expiry;
    }

    /**
     * Iterates through '$models' and synchronizes each one with the stored relating entity.
     *
     * @param   AbstractModel[]  $models  Collection of models to synchronize.
     *
     * @throws  Exception                 If the number of models to synchronize exceeds the maximum set.
     */
    public function synchronizeModels(array $models)
    {
        $modelsCount = count($models);

        if ($modelsCount > $this->maxAutoSync) {
            throw new \Exception(
                "Cannot auto synchronize '{$modelsCount}' models at one time as this surpasses the maximum of '{$this->maxAutoSync}'."
            );
        }

        $update = false;

        foreach ($models as $model) {
            if (!$model instanceof AbstractModel) {
                continue;
            }

            if ($this->synchronizeModel($model, false)) {
                $update = true;
            }
        }

        if ($update) {
            $this->em->flush();
        }
    }

    /**
     * Reads the 'SyncEntity' and 'SyncProperty' annotations defined in the class of '$model'.
     * The information is then used to automatically update the properties of the defined SPIRIT entity for this model,
     * using the values stored in '$model'.
     *
     * @param   AbstractModel  $model         The model to synchronize the defined SPIRIT entity with.
     * @param   bool           $flushChanges  If 'true', the changes are written to the database at the end of the function.
     *
     * @return  bool                          'true' if database transactions are pending.
     */
    public function synchronizeModel(AbstractModel $model, bool $flushChanges = true): bool
    {
        $spiritEntity = $this->getEntityForModel($model);

        // don't continue to synchronize if the entity cannot be found.
        if (null === $spiritEntity || $this->entityIsSynchronized($spiritEntity)) {
            return false;
        }

        $this->updateEntityFromModel($spiritEntity, $model);

        if ($flushChanges) {
            $this->em->flush();

            // since the database transactions have been taken care of already,
            // return 'false' to indicate that there are no further transactions pending.
            return false;
        }

        return true;
    }

    /**
     * Retrieves the SPIRIT entity related to the model by the annotated SPIRIT ID property.
     * If it cannot be found and '$createIfNotExists' is set to true, the SPIRIT entity is automatically created.
     *
     * @param   AbstractModel  $model              The relating model object used as criteria to fetch the SPIRIT entity.
     * @param   bool           $createIfNotExists  If 'true', create and persist a new entity if not found.
     *
     * @return  SpiritSynchronizableInterface      The found (or created) SPIRIT entity.
     */
    protected function getEntityForModel(AbstractModel $model, bool $createIfNotExists = false)
    {
        $reflectionClass = new \ReflectionClass($model);
        $syncEntityAnnotation = $this->annotationReader->getClassAnnotation(
            $reflectionClass,
            SyncEntity::class
        );

        // ignore this model if it does not implement the 'SyncEntity' annotation,
        // as it cannot be synchronized with a SPIRIT entity.
        if (null === $syncEntityAnnotation) {
            return null;
        }

        // get the entity class and check for the implementation of the required interface.
        $entityClass = $syncEntityAnnotation->getEntity();

        if (!in_array(SpiritSynchronizableInterface::class, class_implements($entityClass))) {
            throw new \Exception(
                "Cannot synchronize the entity of class '$entityClass'" .
                'as it does not implement the interface \'' .  SpiritSynchronizableInterface::class . '\'.'
            );
        }

        // get the SPIRIT entity property identifier.
        $modelIdProperty = $syncEntityAnnotation->getModelId();
        $modelIdAnnotation = $this->annotationReader->getPropertyAnnotation(
            new \ReflectionProperty($reflectionClass->getName(), $modelIdProperty),
            SyncProperty::class
        );

        if (null === $modelIdAnnotation) {
            throw new \Exception(
                "Cannot synchronize the entity of class '$entityClass'" .
                "as the identifier of '$modelIdProperty' has not been annotated with the '" .
                SyncProperty::class .
                '\' annotation.'
            );
        }

        $entityIdProperty = $modelIdAnnotation->getProperty();

        // find the SPIRIT entity.
        // TODO: This condition by class not equaling SpiritProductUsage is due to
        // spirit not returning an id in the ProductUsage response. They have been asked
        // to implement this so we have some uniqueness ID from their side for our synching.
        // When they add it, two things must happen:
        // 1. The spirit_id of any persisted SpiritProductUsage entities must be updated in our DB.
        // 2. Once 1 is done, this check by class type can be removed as synching will work as it should.
        $spiritEntity = null;
        if ($entityClass !== "\Edcoms\SpiritApiBundle\Entity\SpiritProductUsage") {
            $spiritEntity = $this->em->getRepository($entityClass)->findOneBy([
                $entityIdProperty => $model->{$modelIdProperty}
            ]);
        }

        if (null === $spiritEntity && $createIfNotExists) {
            // create the entity as it does not exist.
            // automatically set the SPIRIT ID.
            $entityIdSetter = 'set' . ucfirst($entityIdProperty);
            $modelIdValue = $model->{$modelIdProperty};

            $spiritEntity = new $entityClass();
            $spiritEntity->{$entityIdSetter}($modelIdValue);

            $this->em->persist($spiritEntity);
        }

        return $spiritEntity;
    }

    /**
     * Updates the properties of '$spiritEntity' from the annotations and values contained in '$model'.
     *
     * @param   SpiritSynchronizableInterface  $spiritEntity  The entity to update.
     * @param   AbstractModel                  $model         The source of the values to update from.
     */
    protected function updateEntityFromModel(SpiritSynchronizableInterface $spiritEntity, AbstractModel $model)
    {
        $modelClass = get_class($model);
        $modelProperties = $modelClass::getPublicProperties();

        foreach ($modelProperties as $modelProperty) {
            $annotation = $this->annotationReader->getPropertyAnnotation(
                $modelProperty,
                SyncMultipleProperty::class
            );

            $property = $modelProperty->getName();

            // skip property if annotation cannot be found as this is not annotated as a synchronizable property.
            if (null === $annotation) {
                $annotation = $this->annotationReader->getPropertyAnnotation(
                    $modelProperty,
                    SyncProperty::class
                );

                // skip property if annotation cannot be found as this is not annotated as a synchronizable property.
                if (null === $annotation) {
                    continue;
                }

                $this->updateEntityFromModelUsingProperty($spiritEntity, $model, $property, $annotation);
            } else {
                foreach ($annotation->getProperties() as $annotation) {
                    $this->updateEntityFromModelUsingProperty($spiritEntity, $model, $property, $annotation);
                }
            }
        }

        // update the last synchronized timestamp.
        $spiritEntity->setLastSynchronized(new \DateTime());
    }

    protected function updateEntityFromModelUsingProperty(
        SpiritSynchronizableInterface $spiritEntity,
        AbstractModel $model,
        string $property,
        SyncProperty $annotation
    ) {
        // grab the model's value for the current iterating property.
        $modelPropertyValue = $model->{$property};

        if ($annotation->getSubSyncProperty() !== null) {
            $subPropertyPaths = explode('.', $annotation->getSubSyncProperty());

            foreach ($subPropertyPaths as $subPropertyPath) {
                $modelPropertyValue = $modelPropertyValue->{$subPropertyPath};
            }

            if ($annotation->getNormalizeEntity() !== null) {
                $normalizationDetails = $annotation->getNormalizeEntity();

                $normalizedEntity = $this->em->getRepository($normalizationDetails['class'])->findOneBy([
                    $normalizationDetails['property'] => $modelPropertyValue
                ]);

                if (null === $normalizedEntity) {
                    $subEntityClass = $normalizationDetails['class'];
                    $subEntityPropertySetter = 'set' . ucfirst($normalizationDetails['property']);
                    $normalizedEntity = new $subEntityClass();
                    $normalizedEntity->{$subEntityPropertySetter}($modelPropertyValue);

                    // set the last synchronized timestamp so that it's automatically expired
                    // indicating that these entities needs a fresh synchronization.
                    $lastSynchronized = new \DateTime();
                    $lastSynchronized->modify("-{$this->expiry} day" . ($this->expiry === 1 ? '' : 's'));
                    $normalizedEntity->setLastSynchronized($lastSynchronized);

                    $this->em->persist($normalizedEntity);
                }

                $modelPropertyValue = $normalizedEntity;
            }
        }

        $entityGetter = 'get' . ucfirst($annotation->getProperty());
        $entitySetter = 'set' . ucfirst($annotation->getProperty());

        // check the value of the entity first and make sure it's not the same as the model's value.
        if ($spiritEntity->{$entityGetter}() !== $modelPropertyValue) {
            $spiritEntity->{$entitySetter}($modelPropertyValue);
        }
    }
}
