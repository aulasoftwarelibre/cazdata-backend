<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Hunter;
use App\Entity\Journey;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use function in_array;

class JourneyVoter extends Voter
{
    public const OWNER = 'OWNER';

    /**
     * @inheritDoc
     */
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::OWNER])
            && $subject instanceof Journey;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (! $user instanceof Hunter) {
            return false;
        }

        switch ($attribute) {
            case self::OWNER:
                if ($user->getId() === $subject->getHunter()->getId()) {
                    return true;
                }

                break;
        }

        return false;
    }
}
