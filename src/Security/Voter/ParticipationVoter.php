<?php

namespace App\Security\Voter;

use App\Entity\Player;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class ParticipationVoter extends Voter
{
    public const CREATE = 'PARTICIPATION_CREATE';
    public const DELETE = 'PARTICIPATION_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CREATE, self::DELETE])
            && ($subject instanceof \App\Entity\Participation) || is_null($subject);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CREATE:
                //Check if the user is a player
                return ($user instanceof Player);
            case self::DELETE:
                return ($user instanceof Player && $subject->getPlayer() === $user);
        }

        return false;
    }
}
