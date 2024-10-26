<?php

namespace App\Security\Voter;

use App\Entity\Company;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class VideoGameVoter extends Voter
{

    public const CREATE = 'VIDEOGAME_CREATE';
    public const MODIFY = 'VIDEOGAME_MODIFY';
    public const DELETE = 'VIDEOGAME_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CREATE, self::MODIFY,self::DELETE])
            && ($subject instanceof \App\Entity\VideoGame) || is_null($subject);
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
                return ($user instanceof Company);
            case self::MODIFY:
                return ($user instanceof Company && $user->getId() == $subject->getCompany()->getId());
            case self::DELETE:
                return ($user instanceof Company && $user->getId() == $subject->getCompany()->getId()) || in_array("ROLE_ADMIN",$user->getRoles());

        }

        return false;
    }
}
