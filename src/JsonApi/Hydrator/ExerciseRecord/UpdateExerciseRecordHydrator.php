<?php

namespace App\JsonApi\Hydrator\ExerciseRecord;

use App\Entity\ExerciseRecord;

/**
 * Update ExerciseRecord Hydrator.
 */
class UpdateExerciseRecordHydrator extends AbstractExerciseRecordHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($exerciseRecord): array
    {
        return [
            'title' => function (ExerciseRecord $exerciseRecord, $attribute, $data, $attributeName) {
                $exerciseRecord->setTitle($attribute);
            },
            'dateRecorded' => function (ExerciseRecord $exerciseRecord, $attribute, $data, $attributeName) {
                $exerciseRecord->setDateRecorded(new \DateTime($attribute));
            },
            'isComplete' => function (ExerciseRecord $exerciseRecord, $attribute, $data, $attributeName) {
                $exerciseRecord->setIsComplete($attribute);
            },
        ];
    }
}
