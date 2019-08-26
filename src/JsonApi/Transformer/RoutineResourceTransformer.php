<?php

namespace App\JsonApi\Transformer;

use App\Entity\Routine;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * Routine Resource Transformer.
 */
class RoutineResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($routine): string
    {
        return 'routines';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($routine): string
    {
        return (string) $routine->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($routine): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($routine): ?Links
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($routine): array
    {
        return [
            'name' => function (Routine $routine) {
                return $routine->getName();
            },
            'is-active' => function (Routine $routine) {
                return $routine->getIsActive();
            }
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($routine): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($routine): array
    {
        return [
        ];
    }
}
