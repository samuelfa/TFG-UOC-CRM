<?php

namespace App\Domain\Employee;

use App\Domain\User\User;

class Worker extends User
{

    public function getRoles(): array
    {
        return [
            'ROLE_WORKER',
            'ROLE_USER'
        ];
    }
}
