<?php


namespace App\Domain\Company;


use App\Domain\Manager\Manager;

interface CloneCustomerRepository
{
    public function create(string $namespace, Manager $manager): void;
}