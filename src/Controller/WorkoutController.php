<?php

namespace App\Controller;

use App\Entity\Workout;
use App\JsonApi\Document\Workout\WorkoutDocument;
use App\JsonApi\Document\Workout\WorkoutsDocument;
use App\JsonApi\Hydrator\Workout\CreateWorkoutHydrator;
use App\JsonApi\Hydrator\Workout\UpdateWorkoutHydrator;
use App\JsonApi\Transformer\WorkoutResourceTransformer;
use App\Repository\WorkoutRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/workouts")
 */
class WorkoutController extends Controller
{
    /**
     * @Route("/", name="workouts_index", methods="GET")
     */
    public function index(WorkoutRepository $workoutRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($workoutRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new WorkoutsDocument(new WorkoutResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("/", name="workouts_new", methods="POST")
     */
    public function new(ValidatorInterface $validator): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $workout = $this->jsonApi()->hydrate(new CreateWorkoutHydrator($entityManager), new Workout());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($workout);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($workout);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new WorkoutDocument(new WorkoutResourceTransformer()),
            $workout
        );
    }

    /**
     * @Route("/{id}", name="workouts_show", methods="GET")
     */
    public function show(Workout $workout): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new WorkoutDocument(new WorkoutResourceTransformer()),
            $workout
        );
    }

    /**
     * @Route("/{id}", name="workouts_edit", methods="PATCH")
     */
    public function edit(Workout $workout, ValidatorInterface $validator): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $workout = $this->jsonApi()->hydrate(new UpdateWorkoutHydrator($entityManager), $workout);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($workout);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new WorkoutDocument(new WorkoutResourceTransformer()),
            $workout
        );
    }

    /**
     * @Route("/{id}", name="workouts_delete", methods="DELETE")
     */
    public function delete(Request $request, Workout $workout): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($workout);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
