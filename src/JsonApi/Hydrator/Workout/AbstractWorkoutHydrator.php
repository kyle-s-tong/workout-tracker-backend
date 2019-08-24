<?php

namespace App\JsonApi\Hydrator\Workout;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Workout;
use Paknahad\JsonApiBundle\Hydrator\ValidatorTrait;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Doctrine\ORM\Query\Expr;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use Paknahad\JsonApiBundle\Exception\InvalidRelationshipValueException;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

/**
 * Abstract Workout Hydrator.
 */
abstract class AbstractWorkoutHydrator extends AbstractHydrator
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
        return ['workouts'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($workout): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        $this->validateFields($this->objectManager->getClassMetadata(Workout::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($workout, string $id): void
    {
        if ($id && (string) $workout->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body bust be same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($workout): array
    {
        return [
            'user' => function (Workout $workout, ToOneRelationship $user, $data, $relationshipName) {
                $this->validateRelationType($user, ['users']);


                $association = null;
                $identifier = $user->getResourceIdentifier();
                if ($identifier) {
                    $association = $this->objectManager->getRepository('App\Entity\User')
                        ->find($identifier->getId());

                    if (is_null($association)) {
                        throw new InvalidRelationshipValueException($relationshipName, [$identifier->getId()]);
                    }
                }

                $workout->setUser($association);
            },
            'exercises' => function (Workout $workout, ToManyRelationship $exercises, $data, $relationshipName) {
                $this->validateRelationType($exercises, ['exercises']);

                if (count($exercises->getResourceIdentifierIds()) > 0) {
                    $association = $this->objectManager->getRepository('App\Entity\Exercise')
                        ->createQueryBuilder('e')
                        ->where((new Expr())->in('e.id', $exercises->getResourceIdentifierIds()))
                        ->getQuery()
                        ->getResult();

                    $this->validateRelationValues($association, $exercises->getResourceIdentifierIds(), $relationshipName);
                } else {
                    $association = [];
                }

                if ($workout->getExercises()->count() > 0) {
                    foreach ($workout->getExercises() as $exercise) {
                        $workout->removeExercise($exercise);
                    }
                }

                foreach ($association as $exercise) {
                    $workout->addExercise($exercise);
                }
            },
            'workoutRecords' => function (Workout $workout, ToManyRelationship $workoutRecords, $data, $relationshipName) {
                $this->validateRelationType($workoutRecords, ['workout_records']);

                if (count($workoutRecords->getResourceIdentifierIds()) > 0) {
                    $association = $this->objectManager->getRepository('App\Entity\WorkoutRecord')
                        ->createQueryBuilder('w')
                        ->where((new Expr())->in('w.id', $workoutRecords->getResourceIdentifierIds()))
                        ->getQuery()
                        ->getResult();

                    $this->validateRelationValues($association, $workoutRecords->getResourceIdentifierIds(), $relationshipName);
                } else {
                    $association = [];
                }

                if ($workout->getWorkoutRecords()->count() > 0) {
                    foreach ($workout->getWorkoutRecords() as $workoutRecord) {
                        $workout->removeWorkoutRecord($workoutRecord);
                    }
                }

                foreach ($association as $workoutRecord) {
                    $workout->addWorkoutRecord($workoutRecord);
                }
            },
        ];
    }
}
