<?php

namespace App\Domain\Employee;

use App\Domain\Repository;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;

interface WorkerRepository extends Repository
{
    public function findOneByNif(NIF $nif): ?Worker;
    public function findOneByEmailAddress(EmailAddress $emailAddress): ?Worker;
    /**
     * @return Manager[]
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function findAll();
}
