<?php

namespace App\JsonApi\Transformer;

use App\Entity\Workout;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * Workout Resource Transformer.
 */
class WorkoutResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($workout): string
    {
        return 'workouts';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($workout): string
    {
        return (string) $workout->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($workout): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($workout): ?Links
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($workout): array
    {
        return [
            'title' => function (Workout $workout) {
                return $workout->getTitle();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($workout): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($workout): array
    {
        return [
            'user' => function (Workout $workout) {
                return ToOneRelationship::create()
                    ->setData($workout->getUser(), new UserResourceTransformer());
            },
            'exercises' => function (Workout $workout) {
                return ToManyRelationship::create()
                    ->setData($workout->getExercises(), new ExerciseResourceTransformer());
            },
            'workoutRecords' => function (Workout $workout) {
                return ToManyRelationship::create()
                    ->setData($workout->getWorkoutRecords(), new WorkoutRecordResourceTransformer());
            },
        ];
    }
}
