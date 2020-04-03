<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use DateTime;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AnimalRepository")
 * @Vich\Uploadable
 */
final class Animal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(min = 3, max = 64)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;

    /**
     * @ORM\Column(type="string", length=5)
     * @Assert\Regex("^major$|^minor$")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $imageName;

    /**
     * @Vich\UploadableField(mapping="animal_image", fileNameProperty="imageName")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;


    public function __construct(string $name, bool $isEnabled, string $type, string $imageName, File $imageFile)
    {
        $this->name = $name;
        $this->isEnabled = $isEnabled;
        $this->type = $type;
        $this->changeImage($imageName, $imageFile);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function enable(): void
    {
        if($this->isEnabled === false) {
            $this->isEnabled = true;
        }
    }

    public function disable(): void
    {
        if($this->isEnabled === true) {
            $this->isEnabled = false;
        }
    }

    public function changeImage(string $imageName, File $imageFile): void
    {
        $this->imageName = $imageName;
        $this->imageFile = $imageFile;
        $this->updatedAt = new DateTime('now');
    }

}
