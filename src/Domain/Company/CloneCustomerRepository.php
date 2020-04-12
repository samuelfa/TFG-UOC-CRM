<?php


namespace App\Domain\Company;


use App\Domain\Employee\Manager;

interface CloneCustomerRepository
{
    public function create(string $namespace, Manager $manager): void;
}