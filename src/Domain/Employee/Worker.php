<?php

namespace App\Domain\Employee;


class Worker extends Employee
{

    public function getRoles(): array
    {
        return [
            'ROLE_WORKER',
            'ROLE_USER'
        ];
    }
}
