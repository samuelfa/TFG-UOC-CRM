<?php


namespace App\Domain\Company;


class CompanyNotFound extends \RuntimeException
{
    public function __construct(string $namespace)
    {
        parent::__construct("Company {$namespace} not found");
    }

}