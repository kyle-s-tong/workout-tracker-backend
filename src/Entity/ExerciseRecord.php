<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExerciseRecordRepository")
 */
class ExerciseRecord
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRecorded;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isComplete;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Exercise", inversedBy="exerciseRecords")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exercise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WorkoutRecord", inversedBy="exerciseRecords")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workoutRecord;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExerciseRecordSet", mappedBy="exerciseRecord", orphanRemoval=true)
     */
    private $sets;

    public function __construct()
    {
        $this->sets = new ArrayCollection();
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

    public function getDateRecorded(): ?\DateTimeInterface
    {
        return $this->dateRecorded;
    }

    public function setDateRecorded(?\DateTimeInterface $dateRecorded): self
    {
        $this->dateRecorded = $dateRecorded;

        return $this;
    }

    public function getIsComplete(): ?bool
    {
        return $this->isComplete;
    }

    public function setIsComplete(bool $isComplete): self
    {
        $this->isComplete = $isComplete;

        return $this;
    }

    public function getExercise(): ?Exercise
    {
        return $this->exercise;
    }

    public function setExercise(?Exercise $exercise): self
    {
        $this->exercise = $exercise;

        return $this;
    }

    public function getWorkoutRecord(): ?WorkoutRecord
    {
        return $this->workoutRecord;
    }

    public function setWorkoutRecord(?WorkoutRecord $workoutRecord): self
    {
        $this->workoutRecord = $workoutRecord;

        return $this;
    }

    /**
     * @return Collection|ExerciseRecordSet[]
     */
    public function getSets(): Collection
    {
        return $this->sets;
    }

    public function addSet(ExerciseRecordSet $set): self
    {
        if (!$this->sets->contains($set)) {
            $this->sets[] = $set;
            $set->setExerciseRecord($this);
        }

        return $this;
    }

    public function removeSet(ExerciseRecordSet $set): self
    {
        if ($this->sets->contains($set)) {
            $this->sets->removeElement($set);
            // set the owning side to null (unless already changed)
            if ($set->getExerciseRecord() === $this) {
                $set->setExerciseRecord(null);
            }
        }

        return $this;
    }
}
