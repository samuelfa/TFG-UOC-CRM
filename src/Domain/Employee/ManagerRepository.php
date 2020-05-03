<?php

namespace App\Domain\Employee;

use App\Domain\Repository;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;

interface ManagerRepository extends Repository
{
    public function findOneByNif(NIF $nif): ?Manager;
    public function findOneByEmailAddress(EmailAddress $emailAddress): ?Manager;

    /**
     * @return Manager[]
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function findAll();
    public function save(Manager $manager): void;
    public function remove(Manager $manager): void;
}
