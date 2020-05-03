<?php


namespace App\Domain\Familiar\Action;

use App\Domain\Repository;

interface EmailRepository extends Repository
{
    public function save(Email $email): void;
    public function remove(Email $email): void;
}