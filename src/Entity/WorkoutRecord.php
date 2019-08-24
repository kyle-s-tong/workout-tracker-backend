<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkoutRecordRepository")
 */
class WorkoutRecord
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRecorded;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Workout", inversedBy="workoutRecords")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workout;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExerciseRecord", mappedBy="workoutRecord", orphanRemoval=true)
     */
    private $exerciseRecords;

    public function __construct()
    {
        $this->exerciseRecords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRecorded(): ?\DateTimeInterface
    {
        return $this->dateRecorded;
    }

    public function setDateRecorded(?\DateTimeInterface $dateRecorded): self
    {
        $this->dateRecorded = $dateRecorded;

        return $this;
    }

    public function getWorkout(): ?Workout
    {
        return $this->workout;
    }

    public function setWorkout(?Workout $workout): self
    {
        $this->workout = $workout;

        return $this;
    }

    /**
     * @return Collection|ExerciseRecord[]
     */
    public function getExerciseRecords(): Collection
    {
        return $this->exerciseRecords;
    }

    public function addExerciseRecord(ExerciseRecord $exerciseRecord): self
    {
        if (!$this->exerciseRecords->contains($exerciseRecord)) {
            $this->exerciseRecords[] = $exerciseRecord;
            $exerciseRecord->setWorkoutRecord($this);
        }

        return $this;
    }

    public function removeExerciseRecord(ExerciseRecord $exerciseRecord): self
    {
        if ($this->exerciseRecords->contains($exerciseRecord)) {
            $this->exerciseRecords->removeElement($exerciseRecord);
            // set the owning side to null (unless already changed)
            if ($exerciseRecord->getWorkoutRecord() === $this) {
                $exerciseRecord->setWorkoutRecord(null);
            }
        }

        return $this;
    }
}
