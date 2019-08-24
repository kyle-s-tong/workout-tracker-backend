<?php

namespace App\JsonApi\Transformer;

use App\Entity\ExerciseRecordSet;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * ExerciseRecordSet Resource Transformer.
 */
class ExerciseRecordSetResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($exerciseRecordSet): string
    {
        return 'exercise_record_sets';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($exerciseRecordSet): string
    {
        return (string) $exerciseRecordSet->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($exerciseRecordSet): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($exerciseRecordSet): ?Links
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($exerciseRecordSet): array
    {
        return [
            'reps' => function (ExerciseRecordSet $exerciseRecordSet) {
                return $exerciseRecordSet->getReps();
            },
            'weight' => function (ExerciseRecordSet $exerciseRecordSet) {
                return $exerciseRecordSet->getWeight();
            },
            'rest' => function (ExerciseRecordSet $exerciseRecordSet) {
                return $exerciseRecordSet->getRest();
            },
            'isComplete' => function (ExerciseRecordSet $exerciseRecordSet) {
                return $exerciseRecordSet->getIsComplete();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($exerciseRecordSet): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($exerciseRecordSet): array
    {
        return [
            'exerciseRecord' => function (ExerciseRecordSet $exerciseRecordSet) {
                return ToOneRelationship::create()
                    ->setData($exerciseRecordSet->getExerciseRecord(), new ExerciseRecordResourceTransformer());
            },
        ];
    }
}
