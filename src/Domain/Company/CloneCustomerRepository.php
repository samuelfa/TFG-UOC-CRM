<?php


namespace App\Domain\Company;


interface CloneCustomerRepository
{
    public function create(string $namespace): void;
}