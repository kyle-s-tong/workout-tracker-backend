<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\JsonApi\Document\Exercise\ExerciseDocument;
use App\JsonApi\Document\Exercise\ExercisesDocument;
use App\JsonApi\Hydrator\Exercise\CreateExerciseHydrator;
use App\JsonApi\Hydrator\Exercise\UpdateExerciseHydrator;
use App\JsonApi\Transformer\ExerciseResourceTransformer;
use App\Repository\ExerciseRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/exercises")
 */
class ExerciseController extends Controller
{
    /**
     * @Route("", name="exercises_index", methods="GET")
     */
    public function index(ExerciseRepository $exerciseRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($exerciseRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new ExercisesDocument(new ExerciseResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("", name="exercises_new", methods="POST")
     */
    public function new(ValidatorInterface $validator): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $exercise = $this->jsonApi()->hydrate(new CreateExerciseHydrator($entityManager), new Exercise());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($exercise);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($exercise);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ExerciseDocument(new ExerciseResourceTransformer()),
            $exercise
        );
    }

    /**
     * @Route("/{id}", name="exercises_show", methods="GET")
     */
    public function show(Exercise $exercise): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new ExerciseDocument(new ExerciseResourceTransformer()),
            $exercise
        );
    }

    /**
     * @Route("/{id}", name="exercises_edit", methods="PATCH")
     */
    public function edit(Exercise $exercise, ValidatorInterface $validator): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $exercise = $this->jsonApi()->hydrate(new UpdateExerciseHydrator($entityManager), $exercise);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($exercise);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ExerciseDocument(new ExerciseResourceTransformer()),
            $exercise
        );
    }

    /**
     * @Route("/{id}", name="exercises_delete", methods="DELETE")
     */
    public function delete(Request $request, Exercise $exercise): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($exercise);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
