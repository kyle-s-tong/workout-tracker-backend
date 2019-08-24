<?php

namespace App\JsonApi\Hydrator\Workout;

use App\Entity\Workout;

/**
 * Update Workout Hydrator.
 */
class UpdateWorkoutHydrator extends AbstractWorkoutHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($workout): array
    {
        return [
            'title' => function (Workout $workout, $attribute, $data, $attributeName) {
                $workout->setTitle($attribute);
            },
        ];
    }
}
