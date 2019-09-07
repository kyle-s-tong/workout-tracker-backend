<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExerciseRepository")
 */
class Exercise
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfSets;

    /**
     * @ORM\Column(type="integer")
     */
    private $reps;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rest;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Exercise", inversedBy="superset")
     */
    private $superset;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Workout", inversedBy="exercises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workout;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ExerciseSummary", inversedBy="exercises")
     * @ORM\JoinColumn(nullable=true)
     */
    private $exerciseSummary;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExerciseRecord", mappedBy="exercise", orphanRemoval=true)
     */
    private $exerciseRecords;

    public function __construct()
    {
        $this->superset = new ArrayCollection();
        $this->exerciseRecords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getNumberOfSets(): ?int
    {
        return $this->numberOfSets;
    }

    public function setNumberOfSets(int $numberOfSets): self
    {
        $this->numberOfSets = $numberOfSets;

        return $this;
    }

    public function getReps(): ?int
    {
        return $this->reps;
    }

    public function setReps(int $reps): self
    {
        $this->reps = $reps;

        return $this;
    }

    public function getRest(): ?int
    {
        return $this->rest;
    }

    public function setRest(?int $rest): self
    {
        $this->rest = $rest;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSuperset(): Collection
    {
        return $this->superset;
    }

    public function addSuperset(self $superset): self
    {
        if (!$this->superset->contains($superset)) {
            $this->superset[] = $superset;
        }

        return $this;
    }

    public function removeSuperset(self $superset): self
    {
        if ($this->superset->contains($superset)) {
            $this->superset->removeElement($superset);
        }

        return $this;
    }

    public function getExerciseSummary(): ?ExerciseSummary
    {
        return $this->exerciseSummary;
    }

    public function setExerciseSummary(?ExerciseSummary $exerciseSummary): self
    {
        $this->exerciseSummary = $exerciseSummary;

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
            $exerciseRecord->setExercise($this);
        }

        return $this;
    }

    public function removeExerciseRecord(ExerciseRecord $exerciseRecord): self
    {
        if ($this->exerciseRecords->contains($exerciseRecord)) {
            $this->exerciseRecords->removeElement($exerciseRecord);
            // set the owning side to null (unless already changed)
            if ($exerciseRecord->getExercise() === $this) {
                $exerciseRecord->setExercise(null);
            }
        }

        return $this;
    }
}
