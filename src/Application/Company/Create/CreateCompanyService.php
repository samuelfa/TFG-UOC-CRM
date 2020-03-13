<?php

namespace App\Application\Company\Create;

use App\Domain\Company\Company;
use App\Domain\Company\CompanyRepository;
use App\Domain\ValueObject\EmailAddress;

final class CreateCompanyService
{
    private CompanyRepository $repository;

    public function __construct(CompanyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CreateCompanyDTO $dto): void
    {
        $namespace    = $dto->namespace();
        $name         = $dto->name();
        $emailAddress = new EmailAddress($dto->emailAddress());

        $company = Company::create($namespace, $name, $emailAddress);

        $this->repository->save($company);
    }
}
