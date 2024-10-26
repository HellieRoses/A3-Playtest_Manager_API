<?php

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class PlayerVoter extends Voter
{
    public const DELETE = 'PLAYER_DELETE';
    public const MODIFY = 'PLAYER_MODIFY';

    public function __construct(
        private readonly Security $security
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::DELETE, self::MODIFY])
            && $subject instanceof \App\Entity\Player;
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
            case self::DELETE:
                return ($user instanceof \App\Entity\Player && ($subject->getId() == $user->getId() || $this->security->isGranted('ROLE_ADMIN')));
            case self::MODIFY:
                return ($user instanceof \App\Entity\Player && $subject->getId() == $user->getId());
        }

        return false;
    }
}
