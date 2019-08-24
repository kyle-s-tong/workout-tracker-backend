<?php

namespace App\Controller;

use App\Entity\WorkoutRecord;
use App\JsonApi\Document\WorkoutRecord\WorkoutRecordDocument;
use App\JsonApi\Document\WorkoutRecord\WorkoutRecordsDocument;
use App\JsonApi\Hydrator\WorkoutRecord\CreateWorkoutRecordHydrator;
use App\JsonApi\Hydrator\WorkoutRecord\UpdateWorkoutRecordHydrator;
use App\JsonApi\Transformer\WorkoutRecordResourceTransformer;
use App\Repository\WorkoutRecordRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/workout/records")
 */
class WorkoutRecordController extends Controller
{
    /**
     * @Route("/", name="workout_records_index", methods="GET")
     */
    public function index(WorkoutRecordRepository $workoutRecordRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($workoutRecordRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new WorkoutRecordsDocument(new WorkoutRecordResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("/", name="workout_records_new", methods="POST")
     */
    public function new(ValidatorInterface $validator): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $workoutRecord = $this->jsonApi()->hydrate(new CreateWorkoutRecordHydrator($entityManager), new WorkoutRecord());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($workoutRecord);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($workoutRecord);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new WorkoutRecordDocument(new WorkoutRecordResourceTransformer()),
            $workoutRecord
        );
    }

    /**
     * @Route("/{id}", name="workout_records_show", methods="GET")
     */
    public function show(WorkoutRecord $workoutRecord): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new WorkoutRecordDocument(new WorkoutRecordResourceTransformer()),
            $workoutRecord
        );
    }

    /**
     * @Route("/{id}", name="workout_records_edit", methods="PATCH")
     */
    public function edit(WorkoutRecord $workoutRecord, ValidatorInterface $validator): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $workoutRecord = $this->jsonApi()->hydrate(new UpdateWorkoutRecordHydrator($entityManager), $workoutRecord);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($workoutRecord);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new WorkoutRecordDocument(new WorkoutRecordResourceTransformer()),
            $workoutRecord
        );
    }

    /**
     * @Route("/{id}", name="workout_records_delete", methods="DELETE")
     */
    public function delete(Request $request, WorkoutRecord $workoutRecord): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($workoutRecord);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
