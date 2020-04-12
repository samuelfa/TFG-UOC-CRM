<?php

namespace App\Domain\Customer;

use App\Domain\ValueObject\NIF;

interface CustomerRepository
{
    public function findOneByNif(NIF $nif): ?Customer;
    public function save(Customer $company): void;
}
