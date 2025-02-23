<?php

namespace App\JsonApi\Hydrator\WorkoutRecord;

use Doctrine\ORM\Query\Expr;
use App\Entity\WorkoutRecord;
use Paknahad\JsonApiBundle\Hydrator\ValidatorTrait;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use Paknahad\JsonApiBundle\Exception\InvalidRelationshipValueException;

/**
 * Abstract WorkoutRecord Hydrator.
 */
abstract class AbstractWorkoutRecordHydrator extends AbstractHydrator
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
        return ['workout-records'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($workoutRecord): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        $this->validateFields($this->objectManager->getClassMetadata(WorkoutRecord::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($workoutRecord, string $id): void
    {
        if ($id && (string) $workoutRecord->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body bust be same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($workoutRecord): array
    {
        return [
            'workout' => function (WorkoutRecord $workoutRecord, ToOneRelationship $workout, $data, $relationshipName) {
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

                $workoutRecord->setWorkout($association);
            },
            'exercise-records' => function (WorkoutRecord $workoutRecord, ToManyRelationship $exerciseRecords, $data, $relationshipName) {
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

                if ($workoutRecord->getExerciseRecords()->count() > 0) {
                    foreach ($workoutRecord->getExerciseRecords() as $exerciseRecord) {
                        $workoutRecord->removeExerciseRecord($exerciseRecord);
                    }
                }

                foreach ($association as $exerciseRecord) {
                    $workoutRecord->addExerciseRecord($exerciseRecord);
                }
            },
        ];
    }

    protected function validateFields(\Doctrine\Common\Persistence\Mapping\ClassMetadata $metadata, \WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface $request, bool $validExistance = true): void
    {
        foreach ($request->getResourceAttributes() as $field => $value) {
            if ($validExistance && !$metadata->hasField($this->dashesToCamelCase($field))) {
                throw new ValidatorException('This attribute does not exist');
            }
        }
    }

    private function dashesToCamelCase($string, $capitalizeFirstCharacter = false) 
    {
    
        $str = str_replace('-', '', ucwords($string, '-'));
    
        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }
    
        return $str;
    }
}
