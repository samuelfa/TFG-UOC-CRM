<?php

namespace App\Domain\Customer;

use App\Domain\Repository;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;

interface CustomerRepository extends Repository
{
    public function findOneByNif(NIF $nif): ?Customer;
    public function findOneByEmailAddress(EmailAddress $emailAddress): ?Customer;
    /**
     * @return Customer[]
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function findAll();
    public function save(Customer $company): void;
}
