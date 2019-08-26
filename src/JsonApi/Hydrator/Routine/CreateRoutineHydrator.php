<?php

namespace App\JsonApi\Hydrator\Routine;

use App\Entity\Routine;

/**
 * Create Routine Hydrator.
 */
class CreateRoutineHydrator extends AbstractRoutineHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($routine): array
    {
        return [
            'name' => function (Routine $routine, $attribute, $data, $attributeName) {
                $routine->setName($attribute);
            },
            'is-active' => function (Routine $routine, $attribute, $data, $attributeName) {
                $routine->setIsActive($attribute);
            },
        ];
    }
}
