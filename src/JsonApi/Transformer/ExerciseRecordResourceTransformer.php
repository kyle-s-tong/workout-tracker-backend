<?php

namespace App\JsonApi\Transformer;

use App\Entity\ExerciseRecord;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * ExerciseRecord Resource Transformer.
 */
class ExerciseRecordResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($exerciseRecord): string
    {
        return 'exercise_records';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($exerciseRecord): string
    {
        return (string) $exerciseRecord->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($exerciseRecord): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($exerciseRecord): ?Links
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($exerciseRecord): array
    {
        return [
            'title' => function (ExerciseRecord $exerciseRecord) {
                return $exerciseRecord->getTitle();
            },
            'dateRecorded' => function (ExerciseRecord $exerciseRecord) {
                return $exerciseRecord->getDateRecorded()->format(DATE_ATOM);
            },
            'isComplete' => function (ExerciseRecord $exerciseRecord) {
                return $exerciseRecord->getIsComplete();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($exerciseRecord): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($exerciseRecord): array
    {
        return [
            'exercise' => function (ExerciseRecord $exerciseRecord) {
                return ToOneRelationship::create()
                    ->setData($exerciseRecord->getExercise(), new ExerciseResourceTransformer());
            },
            'workoutRecord' => function (ExerciseRecord $exerciseRecord) {
                return ToOneRelationship::create()
                    ->setData($exerciseRecord->getWorkoutRecord(), new WorkoutRecordResourceTransformer());
            },
            'sets' => function (ExerciseRecord $exerciseRecord) {
                return ToManyRelationship::create()
                    ->setData($exerciseRecord->getSets(), new ExerciseRecordSetResourceTransformer());
            },
        ];
    }
}
