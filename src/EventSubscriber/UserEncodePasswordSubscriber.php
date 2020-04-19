<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserEncodePasswordSubscriber implements EventSubscriberInterface
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param GenericEvent<mixed> $event
     */
    public function onEasyAdminEvent(GenericEvent $event) : void
    {
        $subject = $event->getSubject();

        if (! $subject instanceof User) {
            return;
        }

        if ($subject->getPlainPassword() === null) {
            return;
        }

        $password = $this->encoder->encodePassword($subject, $subject->getPlainPassword());

        $subject->setPassword($password);
        $subject->eraseCredentials();
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::PRE_PERSIST => 'onEasyAdminEvent',
            EasyAdminEvents::PRE_UPDATE => 'onEasyAdminEvent',
        ];
    }
}
