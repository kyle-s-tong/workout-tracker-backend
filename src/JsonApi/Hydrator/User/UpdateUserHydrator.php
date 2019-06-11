<?php

namespace App\JsonApi\Hydrator\User;

use App\Entity\User;

/**
 * Update User Hydrator.
 */
class UpdateUserHydrator extends AbstractUserHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($user): array
    {
        return [
            'email' => function (User $user, $attribute, $data, $attributeName) {
                $user->setEmail($attribute);
            },
            'roles' => function (User $user, $attribute, $data, $attributeName) {
                $user->setRoles($attribute);
            },
            'password' => function (User $user, $attribute, $data, $attributeName) {
                $user->setPassword($attribute);
            },
        ];
    }
}
