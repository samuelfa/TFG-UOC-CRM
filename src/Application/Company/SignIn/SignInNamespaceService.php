<?php

namespace App\Application\Company\SignIn;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Company\CompanyNotFound;
use App\Domain\Company\CompanyRepository;

final class SignInNamespaceService implements TransactionalService
{
    private CompanyRepository $repository;
    private string $domainTemplate;

    public function __construct(CompanyRepository $repository, string $domainTemplate)
    {
        $this->repository = $repository;
        $this->domainTemplate = $domainTemplate;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var SignInNamespaceDTO $dto */
        $namespace = $dto->namespace();
        $company   = $this->repository->findOneByNamespace($namespace);
        if(!$company){
            throw new CompanyNotFound($namespace);
        }

        $uri = $this->calculateURI($namespace);
        $dto->setUri($uri);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return SignInNamespaceDTO::class;
    }

    private function calculateURI(string $namespace): string
    {
        return str_replace('{namespace}', $namespace, $this->domainTemplate);
    }
}
