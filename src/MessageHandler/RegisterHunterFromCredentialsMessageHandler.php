<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Hunter;
use App\Message\RegisterHunterFromCredentialsMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RegisterHunterFromCredentialsMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(RegisterHunterFromCredentialsMessage $message) : Hunter
    {
        $hunter = new Hunter();

        $hunter->setUuid($message->getUuid());
        $hunter->setEmail($message->getEmail());
        $hunter->setDisplayName($message->getDisplayName());
        $hunter->setPhotoUrl($message->getPhotoUrl());
        $hunter->setIsEmailVerified($message->isEmailVerified());

        $this->manager->persist($hunter);

        return $hunter;
    }
}
