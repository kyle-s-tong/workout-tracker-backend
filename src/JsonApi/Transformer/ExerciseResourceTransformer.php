<?php

namespace App\JsonApi\Transformer;

use App\Entity\Exercise;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * Exercise Resource Transformer.
 */
class ExerciseResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($exercise): string
    {
        return 'exercises';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($exercise): string
    {
        return (string) $exercise->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($exercise): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($exercise): ?Links
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($exercise): array
    {
        return [
            'title' => function (Exercise $exercise) {
                return $exercise->getTitle();
            },
            'number-of-sets' => function (Exercise $exercise) {
                return $exercise->getNumberOfSets();
            },
            'reps' => function (Exercise $exercise) {
                return $exercise->getReps();
            },
            'rest' => function (Exercise $exercise) {
                return $exercise->getRest();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($exercise): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($exercise): array
    {
        return [
            'superset' => function (Exercise $exercise) {
                return ToManyRelationship::create()
                    ->setData($exercise->getSuperset(), new ExerciseResourceTransformer());
            },
            'workout' => function (Exercise $exercise) {
                return ToOneRelationship::create()
                    ->setData($exercise->getWorkout(), new WorkoutResourceTransformer());
            },
            // TODO: Maybe add back in later. Not worrying about summaries for now.
            // 'exerciseSummary' => function (Exercise $exercise) {
            //     return ToOneRelationship::create()
            //         ->setData($exercise->getExerciseSummary(), new ExerciseSummaryResourceTransformer());
            // },
            'exercise-records' => function (Exercise $exercise) {
                return ToManyRelationship::create()
                    ->setData($exercise->getExerciseRecords(), new ExerciseRecordResourceTransformer());
            },
        ];
    }
}
