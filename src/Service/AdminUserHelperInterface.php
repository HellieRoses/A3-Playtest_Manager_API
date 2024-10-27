<?php

namespace App\Service;

interface AdminUserHelperInterface
{
    public function verifyLogin(?string $login): bool;
}