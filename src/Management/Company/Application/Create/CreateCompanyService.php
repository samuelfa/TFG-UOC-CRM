<?php

namespace App\Management\Company\Application\Create;

use App\Management\Company\Domain\Company;
use App\Management\Company\Domain\CompanyRepository;
use App\Shared\Domain\Factory\UuidFactory;
use App\Shared\Domain\ValueObject\EmailAddress;

final class CreateCompanyService
{
    private CompanyRepository $repository;
    private UuidFactory $factory;

    public function __construct(CompanyRepository $repository, UuidFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    public function execute(CreateCompanyDTO $dto): CreateCompanyDTO
    {
        $id           = $this->factory->create();
        $name         = $dto->name();
        $emailAddress = new EmailAddress($dto->emailAddress());

        $company = Company::create($id, $name, $emailAddress);

        $this->repository->save($company);

        return $dto->createFromUuid($id);
    }
}
