<?php

namespace App\JsonApi\Transformer;

use App\Entity\WorkoutRecord;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * WorkoutRecord Resource Transformer.
 */
class WorkoutRecordResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($workoutRecord): string
    {
        return 'workout_records';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($workoutRecord): string
    {
        return (string) $workoutRecord->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($workoutRecord): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($workoutRecord): ?Links
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($workoutRecord): array
    {
        return [
            'dateRecorded' => function (WorkoutRecord $workoutRecord) {
                return $workoutRecord->getDateRecorded()->format(DATE_ATOM);
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($workoutRecord): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($workoutRecord): array
    {
        return [
            'workout' => function (WorkoutRecord $workoutRecord) {
                return ToOneRelationship::create()
                    ->setData($workoutRecord->getWorkout(), new WorkoutResourceTransformer());
            },
            'exerciseRecords' => function (WorkoutRecord $workoutRecord) {
                return ToManyRelationship::create()
                    ->setData($workoutRecord->getExerciseRecords(), new ExerciseRecordResourceTransformer());
            },
        ];
    }
}
