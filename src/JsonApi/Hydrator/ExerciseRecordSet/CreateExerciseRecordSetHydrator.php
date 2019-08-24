<?php

namespace App\JsonApi\Hydrator\ExerciseRecordSet;

use App\Entity\ExerciseRecordSet;

/**
 * Create ExerciseRecordSet Hydrator.
 */
class CreateExerciseRecordSetHydrator extends AbstractExerciseRecordSetHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($exerciseRecordSet): array
    {
        return [
            'reps' => function (ExerciseRecordSet $exerciseRecordSet, $attribute, $data, $attributeName) {
                $exerciseRecordSet->setReps($attribute);
            },
            'weight' => function (ExerciseRecordSet $exerciseRecordSet, $attribute, $data, $attributeName) {
                $exerciseRecordSet->setWeight($attribute);
            },
            'rest' => function (ExerciseRecordSet $exerciseRecordSet, $attribute, $data, $attributeName) {
                $exerciseRecordSet->setRest($attribute);
            },
            'isComplete' => function (ExerciseRecordSet $exerciseRecordSet, $attribute, $data, $attributeName) {
                $exerciseRecordSet->setIsComplete($attribute);
            },
        ];
    }
}
