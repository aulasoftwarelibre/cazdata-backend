<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnimalRepository")
 *
 * @Vich\Uploadable
 */
final class Animal
{
    public const TYPES = ['minor', 'major'];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\Length(min = 3, max = 64)
     */
    private ?string $name;

    /** @ORM\Column(type="boolean") */
    private bool $isEnabled = true;

    /**
     * @ORM\Column(type="string", length=5)
     *
     * @Assert\Choice(choices="Animal::TYPES", message="animal.choose_valid_type")
     */
    private string $type;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     */
    private ?string $imageName;

    /** @Vich\UploadableField(mapping="animal_image", fileNameProperty="imageName") */
    private ?File $imageFile;

    /** @ORM\Column(type="datetime") */
    private ?DateTimeInterface $updatedAt;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName(string $name) : self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsEnabled() : ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled) : self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getType() : ?string
    {
        return $this->type;
    }

    public function setType(string $type) : self
    {
        $this->type = $type;

        return $this;
    }

    public function getImageName() : ?string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName) : self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageFile() : ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile) : Animal
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            $this->updatedAt = new DateTime();
        }

        return $this;
    }

    public function getUpdatedAt() : ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt) : self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
