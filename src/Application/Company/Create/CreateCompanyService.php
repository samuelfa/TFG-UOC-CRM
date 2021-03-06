<?php

namespace App\Application\Company\Create;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Company\Company;
use App\Domain\Company\CompanyEventDispatcher;
use App\Domain\Company\CompanyRepository;

final class CreateCompanyService implements TransactionalService
{
    private CompanyRepository $repository;
    private CompanyEventDispatcher $dispatcher;

    public function __construct(
        CompanyRepository $repository,
        CompanyEventDispatcher $dispatcher
    )
    {
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var CreateCompanyDTO $dto */
        $namespace    = $dto->namespace();

        if($this->repository->findOneByNamespace($namespace)){
            throw new AlreadyExistsNamespace($namespace);
        }

        $name         = $dto->name();
        $emailAddress = $dto->emailAddress();

        $company = Company::create($namespace, $name, $emailAddress);

        $this->repository->save($company);

        $this->dispatcher->created($dto);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return CreateCompanyDTO::class;
    }
}
