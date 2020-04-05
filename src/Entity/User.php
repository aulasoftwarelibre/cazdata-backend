<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use function array_unique;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /** @ORM\Column(type="string", length=180, unique=true) */
    private ?string $username;

    /**
     * @ORM\Column(type="json")
     *
     * @var array<string>
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string")
     *
     * @var string The hashed password
     */
    private string $password;

    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername() : string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username) : self
    {
        $this->username = $username;

        return $this;
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
    public function getPassword() : string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password) : self
    {
        $this->password = $password;

        return $this;
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
}
