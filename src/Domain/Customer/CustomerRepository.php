<?php

namespace App\Domain\Customer;

interface CustomerRepository
{
    public function findOneByNif(string $namespace): ?Customer;
    public function save(Customer $company): void;
}
