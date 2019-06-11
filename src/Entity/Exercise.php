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
     * @ORM\ManyToMany(targetEntity="App\Entity\Workout", inversedBy="exercises")
     */
    private $workout;

    public function __construct()
    {
        $this->workout = new ArrayCollection();
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

    /**
     * @return Collection|Workout[]
     */
    public function getWorkout(): Collection
    {
        return $this->workout;
    }

    public function addWorkout(Workout $workout): self
    {
        if (!$this->workout->contains($workout)) {
            $this->workout[] = $workout;
        }

        return $this;
    }

    public function removeWorkout(Workout $workout): self
    {
        if ($this->workout->contains($workout)) {
            $this->workout->removeElement($workout);
        }

        return $this;
    }
}
