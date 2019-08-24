<?php

namespace App\JsonApi\Hydrator\ExerciseRecordSet;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\ExerciseRecordSet;
use Paknahad\JsonApiBundle\Hydrator\ValidatorTrait;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use Paknahad\JsonApiBundle\Exception\InvalidRelationshipValueException;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

/**
 * Abstract ExerciseRecordSet Hydrator.
 */
abstract class AbstractExerciseRecordSetHydrator extends AbstractHydrator
{
    use ValidatorTrait;

    /**
     * {@inheritdoc}
     */
    protected function validateClientGeneratedId(
        string $clientGeneratedId,
        JsonApiRequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ): void {
        if (!empty($clientGeneratedId)) {
            throw $exceptionFactory->createClientGeneratedIdNotSupportedException(
                $request,
                $clientGeneratedId
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function generateId(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    protected function getAcceptedTypes(): array
    {
        return ['exercise_record_sets'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($exerciseRecordSet): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        $this->validateFields($this->objectManager->getClassMetadata(ExerciseRecordSet::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($exerciseRecordSet, string $id): void
    {
        if ($id && (string) $exerciseRecordSet->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body bust be same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($exerciseRecordSet): array
    {
        return [
            'exerciseRecord' => function (ExerciseRecordSet $exerciseRecordSet, ToOneRelationship $exerciseRecord, $data, $relationshipName) {
                $this->validateRelationType($exerciseRecord, ['exercise_records']);


                $association = null;
                $identifier = $exerciseRecord->getResourceIdentifier();
                if ($identifier) {
                    $association = $this->objectManager->getRepository('App\Entity\ExerciseRecord')
                        ->find($identifier->getId());

                    if (is_null($association)) {
                        throw new InvalidRelationshipValueException($relationshipName, [$identifier->getId()]);
                    }
                }

                $exerciseRecordSet->setExerciseRecord($association);
            },
        ];
    }
}
