<?php

namespace App\JsonApi\Hydrator\Workout;

use App\Entity\Workout;

/**
 * Create Workout Hydrator.
 */
class CreateWorkoutHydrator extends AbstractWorkoutHydrator
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
