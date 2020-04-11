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
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\Length(min = 3, max = 64)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type("boolean")
     */
    private bool $isEnabled = true;

    /**
     * @ORM\Column(type="string", length=5)
     *
     * @Assert\Choice(callback="getTypes", choices="Animal::TYPES", message="animal.choose_valid_type")
     * @Assert\NotBlank()
     */
    private ?string $type = null;

    /** @ORM\Column(type="string", length=255) */
    private ?string $imageName = null;

    /**
     * @Vich\UploadableField(mapping="animal_images", fileNameProperty="imageName")
     * @Assert\NotBlank(groups={"new"})
     * @Assert\Image(
     *     allowLandscape=false,
     *     allowPortrait=false
     * )
     */
    private ?File $imageFile = null;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime()
     */
    private ?DateTimeInterface $updatedAt = null;

    public function __toString() : string
    {
        return (string) $this->getName();
    }

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

    public function setImageName(?string $imageName) : self
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

    /**
     * @return array<string>
     */
    public static function getTypes() : array
    {
        return ['minor','major'];
    }
}
