<?php

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class CompanyVoter extends Voter
{
    public const MODIFY = 'COMPANY_MODIFY';
    public const DELETE = 'COMPANY_DELETE';


    public function __construct(
        private readonly Security $security
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::MODIFY, self::DELETE])
            && $subject instanceof \App\Entity\Company;
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
                //Check if the user is a company and if the company is the same as the user connected or if the user connected is an admin
                return ($user instanceof \App\Entity\Company && ($subject->getId() == $user->getId())) || $this->security->isGranted('ROLE_ADMIN');
            case self::MODIFY:
                //Check if the user is a company and if the company is the same as the user connected
                return ($user instanceof \App\Entity\Company && $subject->getId() == $user->getId());
        }

        return false;
    }
}
