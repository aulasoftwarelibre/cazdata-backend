<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use function array_unique;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HunterRepository")
 *
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_USER') and object == user"},
 *     collectionOperations={},
 *     itemOperations={"get"}
 * )
 */
class Hunter implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", unique=true)
     */
    private ?string $id;

    /**
     * @ORM\Column(type="json")
     *
     * @var array<string>
     */
    private array $roles = [];

    /** @ORM\Column(type="string", length=255) */
    private ?string $email;

    /** @ORM\Column(type="string", length=255) */
    private ?string $displayName;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $photoUrl;

    /** @ORM\Column(type="boolean") */
    private bool $isEmailVerified = false;

    /** @ORM\Column(type="boolean") */
    private bool $isEnabled = true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Journey", mappedBy="hunter", orphanRemoval=true)
     *
     * @var Collection<null, Journey>
     */
    private Collection $journeys;

    public function __construct()
    {
        $this->journeys = new ArrayCollection();
    }

    public function getId() : ?string
    {
        return $this->id;
    }

    public function setId(string $id) : self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername() : string
    {
        return (string) $this->id;
    }

    /**
     * @see UserInterface
     *
     * @return array<string>
     */
    public function getRoles() : array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles) : self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword() : ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function getSalt() : ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials() : void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail() : ?string
    {
        return $this->email;
    }

    public function setEmail(string $email) : self
    {
        $this->email = $email;

        return $this;
    }

    public function getDisplayName() : ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName) : self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getPhotoUrl() : ?string
    {
        return $this->photoUrl;
    }

    public function setPhotoUrl(?string $photoUrl) : self
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    public function getIsEmailVerified() : ?bool
    {
        return $this->isEmailVerified;
    }

    public function setIsEmailVerified(bool $isEmailVerified) : self
    {
        $this->isEmailVerified = $isEmailVerified;

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

    /**
     * @return Collection<null,Journey>
     */
    public function getJourneys() : Collection
    {
        return $this->journeys;
    }

    public function addJourney(Journey $journey) : self
    {
        if (! $this->journeys->contains($journey)) {
            $this->journeys[] = $journey;
            $journey->setHunter($this);
        }

        return $this;
    }

    public function removeJourney(Journey $journey) : self
    {
        if ($this->journeys->contains($journey)) {
            $this->journeys->removeElement($journey);
            // set the owning side to null (unless already changed)
            if ($journey->getHunter() === $this) {
                $journey->setHunter(null);
            }
        }

        return $this;
    }
}
