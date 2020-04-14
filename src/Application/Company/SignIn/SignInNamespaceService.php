<?php

namespace App\Application\Company\SignIn;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Company\CompanyNotFound;
use App\Domain\Company\CompanyRepository;
use App\Infrastructure\Symfony\Factory\URLFactory;

final class SignInNamespaceService implements TransactionalService
{
    private CompanyRepository $repository;
    private URLFactory $URLFactory;

    public function __construct(CompanyRepository $repository, URLFactory $URLFactory)
    {
        $this->repository = $repository;
        $this->URLFactory = $URLFactory;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var SignInNamespaceDTO $dto */
        $namespace = $dto->namespace();
        $company   = $this->repository->findOneByNamespace($namespace);
        if(!$company){
            throw new CompanyNotFound($namespace);
        }

        $uri = $this->URLFactory->generate($namespace);
        $dto->setUri($uri);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return SignInNamespaceDTO::class;
    }
}
