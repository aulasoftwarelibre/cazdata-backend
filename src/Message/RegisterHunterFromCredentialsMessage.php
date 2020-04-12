<?php

declare(strict_types=1);

namespace App\Message;

use Lcobucci\JWT\Token;

final class RegisterHunterFromCredentialsMessage
{
    private string $uuid;
    private string $email;
    private string $displayName;
    private string $photoUrl;
    private bool $isEmailVerified;

    public function __construct(Token $credentials)
    {
        $this->uuid            = $credentials->getClaim('sub');
        $this->email           = $credentials->getClaim('email');
        $this->displayName     = $credentials->getClaim('displayName');
        $this->photoUrl        = $credentials->getClaim('photoUrl');
        $this->isEmailVerified = $credentials->getClaim('emailVerified', false);
    }

    public function getUuid() : string
    {
        return $this->uuid;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getDisplayName() : string
    {
        return $this->displayName;
    }

    public function getPhotoUrl() : string
    {
        return $this->photoUrl;
    }

    public function isEmailVerified() : bool
    {
        return $this->isEmailVerified;
    }
}
