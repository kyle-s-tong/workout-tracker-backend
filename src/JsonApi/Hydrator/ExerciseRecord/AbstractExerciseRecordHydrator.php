<?php

namespace App\JsonApi\Hydrator\ExerciseRecord;

use Doctrine\ORM\Query\Expr;
use App\Entity\ExerciseRecord;
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
        return ['exercise-records'];
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
            'workout-record' => function (ExerciseRecord $exerciseRecord, ToOneRelationship $workoutRecord, $data, $relationshipName) {
                $this->validateRelationType($workoutRecord, ['workout-records']);


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
            }
        ];
    }

    protected function validateFields(\Doctrine\Common\Persistence\Mapping\ClassMetadata $metadata, \WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface $request, bool $validExistance = true): void
    {
        foreach ($request->getResourceAttributes() as $field => $value) {
            if ($validExistance && !$metadata->hasField($this->dashesToCamelCase($field))) {
                var_dump($field);
                var_dump($metadata);
                die();
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
