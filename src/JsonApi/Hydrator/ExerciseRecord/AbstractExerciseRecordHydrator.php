<?php

namespace App\JsonApi\Hydrator\ExerciseRecord;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\ExerciseRecord;
use Paknahad\JsonApiBundle\Hydrator\ValidatorTrait;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Doctrine\ORM\Query\Expr;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use Paknahad\JsonApiBundle\Exception\InvalidRelationshipValueException;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

/**
 * Abstract ExerciseRecord Hydrator.
 */
abstract class AbstractExerciseRecordHydrator extends AbstractHydrator
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
        return ['exercise_records'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($exerciseRecord): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        $this->validateFields($this->objectManager->getClassMetadata(ExerciseRecord::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($exerciseRecord, string $id): void
    {
        if ($id && (string) $exerciseRecord->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body bust be same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($exerciseRecord): array
    {
        return [
            'exercise' => function (ExerciseRecord $exerciseRecord, ToOneRelationship $exercise, $data, $relationshipName) {
                $this->validateRelationType($exercise, ['exercises']);


                $association = null;
                $identifier = $exercise->getResourceIdentifier();
                if ($identifier) {
                    $association = $this->objectManager->getRepository('App\Entity\Exercise')
                        ->find($identifier->getId());

                    if (is_null($association)) {
                        throw new InvalidRelationshipValueException($relationshipName, [$identifier->getId()]);
                    }
                }

                $exerciseRecord->setExercise($association);
            },
            'workoutRecord' => function (ExerciseRecord $exerciseRecord, ToOneRelationship $workoutRecord, $data, $relationshipName) {
                $this->validateRelationType($workoutRecord, ['workout_records']);


                $association = null;
                $identifier = $workoutRecord->getResourceIdentifier();
                if ($identifier) {
                    $association = $this->objectManager->getRepository('App\Entity\WorkoutRecord')
                        ->find($identifier->getId());

                    if (is_null($association)) {
                        throw new InvalidRelationshipValueException($relationshipName, [$identifier->getId()]);
                    }
                }

                $exerciseRecord->setWorkoutRecord($association);
            },
            'sets' => function (ExerciseRecord $exerciseRecord, ToManyRelationship $sets, $data, $relationshipName) {
                $this->validateRelationType($sets, ['exercise_record_sets']);

                if (count($sets->getResourceIdentifierIds()) > 0) {
                    $association = $this->objectManager->getRepository('App\Entity\ExerciseRecordSet')
                        ->createQueryBuilder('s')
                        ->where((new Expr())->in('s.id', $sets->getResourceIdentifierIds()))
                        ->getQuery()
                        ->getResult();

                    $this->validateRelationValues($association, $sets->getResourceIdentifierIds(), $relationshipName);
                } else {
                    $association = [];
                }

                if ($exerciseRecord->getSets()->count() > 0) {
                    foreach ($exerciseRecord->getSets() as $set) {
                        $exerciseRecord->removeSet($set);
                    }
                }

                foreach ($association as $set) {
                    $exerciseRecord->addSet($set);
                }
            },
        ];
    }
}
