<?php

namespace App\Service;


use App\Repository\UserRepository;

class AdminUserHelper implements AdminUserHelperInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function verifyLogin(?string $login): bool
    {
        if (empty($login)) {
            return false;
        }
        $countUser = $this->userRepository->findBy(['login' => $login]);
        if (empty($countUser)) {
            return false;
        }

        return true;
    }
}