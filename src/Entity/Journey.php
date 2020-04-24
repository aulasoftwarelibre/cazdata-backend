<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JourneyRepository")
 *
 * @ApiResource(
 *     itemOperations={"get", "delete"},
 *     collectionOperations={"get", "post"},
 * )
 */
class Journey
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="uuid", unique=true)
     */
    private ?string $id;

    /** @ORM\Column(type="string", length=255) */
    private ?string $title;

    /** @ORM\Column(type="integer") */
    private ?int $distance;

    /** @ORM\Column(type="datetime") */
    private ?DateTimeInterface $startsAt;

    /** @ORM\Column(type="datetime") */
    private ?DateTimeInterface $endsAt;

    /** @ORM\Column(type="integer") */
    private ?int $calories;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hunter", inversedBy="journeys")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Hunter $hunter;

    public function getId() : ?string
    {
        return $this->id;
    }

    public function setId(string $id) : self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function setTitle(string $title) : self
    {
        $this->title = $title;

        return $this;
    }

    public function getDistance() : ?int
    {
        return $this->distance;
    }

    public function setDistance(int $distance) : self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getStartsAt() : ?DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(DateTimeInterface $startsAt) : self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt() : ?DateTimeInterface
    {
        return $this->endsAt;
    }

    public function setEndsAt(DateTimeInterface $endsAt) : self
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function getCalories() : ?int
    {
        return $this->calories;
    }

    public function setCalories(int $calories) : self
    {
        $this->calories = $calories;

        return $this;
    }

    public function getHunter() : ?Hunter
    {
        return $this->hunter;
    }

    public function setHunter(?Hunter $hunter) : self
    {
        $this->hunter = $hunter;

        return $this;
    }
}
