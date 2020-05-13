<?php

namespace App\Domain\Familiar;


use App\Domain\Customer\Customer;
use App\Domain\Repository;
use App\Domain\ValueObject\NIF;

interface FamiliarRepository extends Repository
{
    public function findOneByNif(NIF $nif): ?Familiar;
    /**
     * @return Familiar[]
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function findAll();
    public function save(Familiar $familiar): void;
    public function remove(Familiar $familiar): void;
    public function findByCustomer(Customer $customer): array;
    public function total(): int;
}
