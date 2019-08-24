<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExerciseRecordSetRepository")
 */
class ExerciseRecordSet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $reps;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2, nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rest;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isComplete;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ExerciseRecord", inversedBy="sets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exerciseRecord;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight): self
    {
        $this->weight = $weight;

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

    public function getIsComplete(): ?bool
    {
        return $this->isComplete;
    }

    public function setIsComplete(?bool $isComplete): self
    {
        $this->isComplete = $isComplete;

        return $this;
    }

    public function getExerciseRecord(): ?ExerciseRecord
    {
        return $this->exerciseRecord;
    }

    public function setExerciseRecord(?ExerciseRecord $exerciseRecord): self
    {
        $this->exerciseRecord = $exerciseRecord;

        return $this;
    }
}
