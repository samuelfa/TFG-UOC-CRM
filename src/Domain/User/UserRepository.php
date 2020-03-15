<?php

namespace App\Domain\User;

interface UserRepository
{
    public function findOneByNif(string $namespace): ?User;
    public function save(User $company): void;
}
