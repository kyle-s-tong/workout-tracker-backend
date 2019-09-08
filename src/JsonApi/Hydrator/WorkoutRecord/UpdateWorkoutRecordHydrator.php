<?php

namespace App\JsonApi\Hydrator\WorkoutRecord;

use App\Entity\WorkoutRecord;

/**
 * Update WorkoutRecord Hydrator.
 */
class UpdateWorkoutRecordHydrator extends AbstractWorkoutRecordHydrator
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
