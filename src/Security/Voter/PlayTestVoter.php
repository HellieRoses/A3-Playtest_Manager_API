<?php

namespace App\Security\Voter;

use App\Entity\Company;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class PlayTestVoter extends Voter
{
    public const CREATE = 'PLAYTEST_CREATE';
    public const MODIFY = 'PLAYTEST_MODIFY';
    public const DELETE = 'PLAYTEST_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CREATE, self::MODIFY,self::DELETE])
            && ($subject instanceof \App\Entity\PlayTest) || is_null($subject);
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
                 return ($user instanceof Company && $subject->getVideoGame()->getCompany() == $user);
             case self::MODIFY:
                 return ($user instanceof Company && $subject->getCompany() == $user);
            case self::DELETE:
                return ($user instanceof Company && $subject->getCompany() == $user) || in_array("ROLE_ADMIN",$user->getRoles());

        }

        return false;
    }
}
