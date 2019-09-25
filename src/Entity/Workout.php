<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkoutRepository")
 */
class Workout
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="workouts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Exercise", mappedBy="workout", orphanRemoval=true)
     */
    private $exercises;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WorkoutRecord", mappedBy="workout", orphanRemoval=true)
     */
    private $workoutRecords;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Routine", inversedBy="workouts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $routine;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
        $this->workoutRecords = new ArrayCollection();
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Exercise[]
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    public function addExercise(Exercise $exercise): self
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises[] = $exercise;
            $exercise->addWorkout($this);
        }

        return $this;
    }

    public function removeExercise(Exercise $exercise): self
    {
        if ($this->exercises->contains($exercise)) {
            $this->exercises->removeElement($exercise);
            $exercise->removeWorkout($this);
        }

        return $this;
    }

    /**
     * @return Collection|WorkoutRecord[]
     */
    public function getWorkoutRecords(): Collection
    {
        return $this->workoutRecords;
    }

    public function addWorkoutRecord(WorkoutRecord $workoutRecord): self
    {
        if (!$this->workoutRecords->contains($workoutRecord)) {
            $this->workoutRecords[] = $workoutRecord;
            $workoutRecord->setWorkout($this);
        }

        return $this;
    }

    public function removeWorkoutRecord(WorkoutRecord $workoutRecord): self
    {
        if ($this->workoutRecords->contains($workoutRecord)) {
            $this->workoutRecords->removeElement($workoutRecord);
            // set the owning side to null (unless already changed)
            if ($workoutRecord->getWorkout() === $this) {
                $workoutRecord->setWorkout(null);
            }
        }

        return $this;
    }

    public function getRoutine(): ?Routine
    {
        return $this->routine;
    }

    public function setRoutine(?Routine $routine): self
    {
        $this->routine = $routine;

        return $this;
    }
}
