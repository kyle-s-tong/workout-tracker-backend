<?php

namespace App\JsonApi\Hydrator\Routine;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Routine;
use Paknahad\JsonApiBundle\Hydrator\ValidatorTrait;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;
use Symfony\Component\Validator\Validation;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

/**
 * Abstract Routine Hydrator.
 */
abstract class AbstractRoutineHydrator extends AbstractHydrator
{
    use ValidatorTrait;

    /**
     * {@inheritdoc}
     */
    protected function validateClientGeneratedId(
        string $clientGeneratedId,
        JsonApiRequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ): void {
        if (!empty($clientGeneratedId)) {
            throw $exceptionFactory->createClientGeneratedIdNotSupportedException(
                $request,
                $clientGeneratedId
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function generateId(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    protected function getAcceptedTypes(): array
    {
        return ['routines'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($routine): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        
        $this->validateFields($this->objectManager->getClassMetadata(Routine::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($routine, string $id): void
    {
        if ($id && (string) $routine->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body bust be same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($routine): array
    {
        return [
        ];
    }

    protected function validateFields(\Doctrine\Common\Persistence\Mapping\ClassMetadata $metadata, \WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface $request, bool $validExistance = true): void
    {
        foreach ($request->getResourceAttributes() as $field => $value) {
            if ($validExistance && !$metadata->hasField($this->dashesToCamelCase($field))) {
                throw new ValidatorException('This attribute does not exist');
            }
        }
    }


    private function dashesToCamelCase($string, $capitalizeFirstCharacter = false) 
    {
    
        $str = str_replace('-', '', ucwords($string, '-'));
    
        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }
    
        return $str;
    }
}
