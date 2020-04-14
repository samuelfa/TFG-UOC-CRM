<?php

namespace App\Domain\Customer;

use App\Domain\User\User;

class Customer extends User
{
    public function getRoles(): array
    {
        return [
            'ROLE_USER'
        ];
    }
}
