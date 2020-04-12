<?php

namespace App\Domain\User;

use App\Domain\ValueObject\NIF;

interface UserRepository
{
    public function findOneByNif(NIF $nif): ?User;
    public function save(User $user): void;
}
