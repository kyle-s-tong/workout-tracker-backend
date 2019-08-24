<?php

namespace App\Controller;

use App\Entity\ExerciseRecordSet;
use App\JsonApi\Document\ExerciseRecordSet\ExerciseRecordSetDocument;
use App\JsonApi\Document\ExerciseRecordSet\ExerciseRecordSetsDocument;
use App\JsonApi\Hydrator\ExerciseRecordSet\CreateExerciseRecordSetHydrator;
use App\JsonApi\Hydrator\ExerciseRecordSet\UpdateExerciseRecordSetHydrator;
use App\JsonApi\Transformer\ExerciseRecordSetResourceTransformer;
use App\Repository\ExerciseRecordSetRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/exercise/record/sets")
 */
class ExerciseRecordSetController extends Controller
{
    /**
     * @Route("/", name="exercise_record_sets_index", methods="GET")
     */
    public function index(ExerciseRecordSetRepository $exerciseRecordSetRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($exerciseRecordSetRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new ExerciseRecordSetsDocument(new ExerciseRecordSetResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("/", name="exercise_record_sets_new", methods="POST")
     */
    public function new(ValidatorInterface $validator): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $exerciseRecordSet = $this->jsonApi()->hydrate(new CreateExerciseRecordSetHydrator($entityManager), new ExerciseRecordSet());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($exerciseRecordSet);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($exerciseRecordSet);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ExerciseRecordSetDocument(new ExerciseRecordSetResourceTransformer()),
            $exerciseRecordSet
        );
    }

    /**
     * @Route("/{id}", name="exercise_record_sets_show", methods="GET")
     */
    public function show(ExerciseRecordSet $exerciseRecordSet): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new ExerciseRecordSetDocument(new ExerciseRecordSetResourceTransformer()),
            $exerciseRecordSet
        );
    }

    /**
     * @Route("/{id}", name="exercise_record_sets_edit", methods="PATCH")
     */
    public function edit(ExerciseRecordSet $exerciseRecordSet, ValidatorInterface $validator): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $exerciseRecordSet = $this->jsonApi()->hydrate(new UpdateExerciseRecordSetHydrator($entityManager), $exerciseRecordSet);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($exerciseRecordSet);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ExerciseRecordSetDocument(new ExerciseRecordSetResourceTransformer()),
            $exerciseRecordSet
        );
    }

    /**
     * @Route("/{id}", name="exercise_record_sets_delete", methods="DELETE")
     */
    public function delete(Request $request, ExerciseRecordSet $exerciseRecordSet): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($exerciseRecordSet);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
