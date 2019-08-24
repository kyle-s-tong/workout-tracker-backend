<?php

namespace App\JsonApi\Hydrator\Exercise;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Exercise;
use Paknahad\JsonApiBundle\Hydrator\ValidatorTrait;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Doctrine\ORM\Query\Expr;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use Paknahad\JsonApiBundle\Exception\InvalidRelationshipValueException;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

/**
 * Abstract Exercise Hydrator.
 */
abstract class AbstractExerciseHydrator extends AbstractHydrator
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
        return ['exercises'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($exercise): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        $this->validateFields($this->objectManager->getClassMetadata(Exercise::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($exercise, string $id): void
    {
        if ($id && (string) $exercise->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body bust be same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($exercise): array
    {
        return [
            'superset' => function (Exercise $exercise, ToManyRelationship $superset, $data, $relationshipName) {
                $this->validateRelationType($superset, ['exercises']);

                if (count($superset->getResourceIdentifierIds()) > 0) {
                    $association = $this->objectManager->getRepository('App\Entity\Exercise')
                        ->createQueryBuilder('s')
                        ->where((new Expr())->in('s.id', $superset->getResourceIdentifierIds()))
                        ->getQuery()
                        ->getResult();

                    $this->validateRelationValues($association, $superset->getResourceIdentifierIds(), $relationshipName);
                } else {
                    $association = [];
                }

                if ($exercise->getSuperset()->count() > 0) {
                    foreach ($exercise->getSuperset() as $superset) {
                        $exercise->removeSuperset($superset);
                    }
                }

                foreach ($association as $superset) {
                    $exercise->addSuperset($superset);
                }
            },
            'workout' => function (Exercise $exercise, ToOneRelationship $workout, $data, $relationshipName) {
                $this->validateRelationType($workout, ['workouts']);


                $association = null;
                $identifier = $workout->getResourceIdentifier();
                if ($identifier) {
                    $association = $this->objectManager->getRepository('App\Entity\Workout')
                        ->find($identifier->getId());

                    if (is_null($association)) {
                        throw new InvalidRelationshipValueException($relationshipName, [$identifier->getId()]);
                    }
                }

                $exercise->setWorkout($association);
            },
            'exerciseSummary' => function (Exercise $exercise, ToOneRelationship $exerciseSummary, $data, $relationshipName) {
                $this->validateRelationType($exerciseSummary, ['exercise_summaries']);


                $association = null;
                $identifier = $exerciseSummary->getResourceIdentifier();
                if ($identifier) {
                    $association = $this->objectManager->getRepository('App\Entity\ExerciseSummary')
                        ->find($identifier->getId());

                    if (is_null($association)) {
                        throw new InvalidRelationshipValueException($relationshipName, [$identifier->getId()]);
                    }
                }

                $exercise->setExerciseSummary($association);
            },
            'exerciseRecords' => function (Exercise $exercise, ToManyRelationship $exerciseRecords, $data, $relationshipName) {
                $this->validateRelationType($exerciseRecords, ['exercise_records']);

                if (count($exerciseRecords->getResourceIdentifierIds()) > 0) {
                    $association = $this->objectManager->getRepository('App\Entity\ExerciseRecord')
                        ->createQueryBuilder('e')
                        ->where((new Expr())->in('e.id', $exerciseRecords->getResourceIdentifierIds()))
                        ->getQuery()
                        ->getResult();

                    $this->validateRelationValues($association, $exerciseRecords->getResourceIdentifierIds(), $relationshipName);
                } else {
                    $association = [];
                }

                if ($exercise->getExerciseRecords()->count() > 0) {
                    foreach ($exercise->getExerciseRecords() as $exerciseRecord) {
                        $exercise->removeExerciseRecord($exerciseRecord);
                    }
                }

                foreach ($association as $exerciseRecord) {
                    $exercise->addExerciseRecord($exerciseRecord);
                }
            },
        ];
    }
}
