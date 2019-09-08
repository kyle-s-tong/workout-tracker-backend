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
            'date-recorded' => function (ExerciseRecord $exerciseRecord, $attribute, $data, $attributeName) {
                $exerciseRecord->setDateRecorded(new \DateTime($attribute));
            },
            'is-complete' => function (ExerciseRecord $exerciseRecord, $attribute, $data, $attributeName) {
                $exerciseRecord->setIsComplete($attribute);
            },
            'sets' => function (ExerciseRecord $exerciseRecord, $attribute, $data, $attributeName) {
                $exerciseRecord->setSets($attribute);
            },
        ];
    }
}
