<?php

namespace App\Domain\Employee;


class Manager extends Employee
{
    public function getRoles(): array
    {
        return [
            'ROLE_MANAGER',
            'ROLE_WORKER',
            'ROLE_USER'
        ];
    }
}
