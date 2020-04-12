<?php

namespace App\Domain\Worker;

use App\Domain\ValueObject\NIF;

interface WorkerRepository
{
    public function findOneByNif(NIF $nif): ?Worker;
    public function save(Worker $worker): void;
}
