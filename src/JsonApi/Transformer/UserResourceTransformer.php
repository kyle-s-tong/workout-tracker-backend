<?php

namespace App\JsonApi\Transformer;

use App\Entity\User;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * User Resource Transformer.
 */
class UserResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($user): string
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($user): string
    {
        return (string) $user->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($user): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($user): ?Links
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($user): array
    {
        return [
            'email' => function (User $user) {
                return $user->getEmail();
            },
            'roles' => function (User $user) {
                return $user->getRoles();
            },
            'password' => function (User $user) {
                return $user->getPassword();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($user): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($user): array
    {
        return [
        ];
    }
}
