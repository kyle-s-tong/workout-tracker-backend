<?php

namespace App\JsonApi\Hydrator\Exercise;

use App\Entity\Exercise;

/**
 * Update Exercise Hydrator.
 */
class UpdateExerciseHydrator extends AbstractExerciseHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($exercise): array
    {
        return [
            'title' => function (Exercise $exercise, $attribute, $data, $attributeName) {
                $exercise->setTitle($attribute);
            },
            'numberOfSets' => function (Exercise $exercise, $attribute, $data, $attributeName) {
                $exercise->setNumberOfSets($attribute);
            },
            'reps' => function (Exercise $exercise, $attribute, $data, $attributeName) {
                $exercise->setReps($attribute);
            },
            'rest' => function (Exercise $exercise, $attribute, $data, $attributeName) {
                $exercise->setRest($attribute);
            },
        ];
    }
}
