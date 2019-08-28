<?php

namespace App\Controller;

use App\Entity\Routine;
use App\JsonApi\Document\Routine\RoutineDocument;
use App\JsonApi\Document\Routine\RoutinesDocument;
use App\JsonApi\Hydrator\Routine\CreateRoutineHydrator;
use App\JsonApi\Hydrator\Routine\UpdateRoutineHydrator;
use App\JsonApi\Transformer\RoutineResourceTransformer;
use App\Repository\RoutineRepository;
use App\Service\UserService;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/routines")
 */
class RoutineController extends Controller
{
    /**
     * @Route("", name="routines_index", methods="GET")
     */
    public function index(RoutineRepository $routineRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($routineRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new RoutinesDocument(new RoutineResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("", name="routines_new", methods="POST")
     */
    public function new(ValidatorInterface $validator, Request $request, UserService $userService): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $routine = $this->jsonApi()->hydrate(new CreateRoutineHydrator($entityManager), new Routine());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($routine);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $user = $userService->getUserFromRequestHeader($request);
        if ($user) {
            $routine->setUser($user);
        }
        
        $entityManager->persist($routine);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new RoutineDocument(new RoutineResourceTransformer()),
            $routine
        );
    }

    /**
     * @Route("/{id}", name="routines_show", methods="GET")
     */
    public function show(Routine $routine): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new RoutineDocument(new RoutineResourceTransformer()),
            $routine
        );
    }

    /**
     * @Route("/{id}", name="routines_edit", methods="PATCH")
     */
    public function edit(Routine $routine, ValidatorInterface $validator): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $routine = $this->jsonApi()->hydrate(new UpdateRoutineHydrator($entityManager), $routine);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($routine);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new RoutineDocument(new RoutineResourceTransformer()),
            $routine
        );
    }

    /**
     * @Route("/{id}", name="routines_delete", methods="DELETE")
     */
    public function delete(Request $request, Routine $routine): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($routine);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
