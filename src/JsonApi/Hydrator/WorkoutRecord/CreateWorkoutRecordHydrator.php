<?php

namespace App\JsonApi\Hydrator\WorkoutRecord;

use App\Entity\WorkoutRecord;

/**
 * Create WorkoutRecord Hydrator.
 */
class CreateWorkoutRecordHydrator extends AbstractWorkoutRecordHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($workoutRecord): array
    {
        return [
            'date-recorded' => function (WorkoutRecord $workoutRecord, $attribute, $data, $attributeName) {
                $workoutRecord->setDateRecorded(new \DateTime($attribute));
            },
        ];
    }
}
