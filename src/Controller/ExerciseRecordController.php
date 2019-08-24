<?php

namespace App\Controller;

use App\Entity\ExerciseRecord;
use App\JsonApi\Document\ExerciseRecord\ExerciseRecordDocument;
use App\JsonApi\Document\ExerciseRecord\ExerciseRecordsDocument;
use App\JsonApi\Hydrator\ExerciseRecord\CreateExerciseRecordHydrator;
use App\JsonApi\Hydrator\ExerciseRecord\UpdateExerciseRecordHydrator;
use App\JsonApi\Transformer\ExerciseRecordResourceTransformer;
use App\Repository\ExerciseRecordRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/exercise/records")
 */
class ExerciseRecordController extends Controller
{
    /**
     * @Route("/", name="exercise_records_index", methods="GET")
     */
    public function index(ExerciseRecordRepository $exerciseRecordRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($exerciseRecordRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new ExerciseRecordsDocument(new ExerciseRecordResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("/", name="exercise_records_new", methods="POST")
     */
    public function new(ValidatorInterface $validator): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $exerciseRecord = $this->jsonApi()->hydrate(new CreateExerciseRecordHydrator($entityManager), new ExerciseRecord());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($exerciseRecord);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($exerciseRecord);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ExerciseRecordDocument(new ExerciseRecordResourceTransformer()),
            $exerciseRecord
        );
    }

    /**
     * @Route("/{id}", name="exercise_records_show", methods="GET")
     */
    public function show(ExerciseRecord $exerciseRecord): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new ExerciseRecordDocument(new ExerciseRecordResourceTransformer()),
            $exerciseRecord
        );
    }

    /**
     * @Route("/{id}", name="exercise_records_edit", methods="PATCH")
     */
    public function edit(ExerciseRecord $exerciseRecord, ValidatorInterface $validator): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $exerciseRecord = $this->jsonApi()->hydrate(new UpdateExerciseRecordHydrator($entityManager), $exerciseRecord);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($exerciseRecord);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ExerciseRecordDocument(new ExerciseRecordResourceTransformer()),
            $exerciseRecord
        );
    }

    /**
     * @Route("/{id}", name="exercise_records_delete", methods="DELETE")
     */
    public function delete(Request $request, ExerciseRecord $exerciseRecord): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($exerciseRecord);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
