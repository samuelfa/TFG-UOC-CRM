<?php


namespace App\Infrastructure\Symfony\Factory;


use App\Domain\Company\CompanyNotFound;
use App\Domain\Company\CompanyRepository;
use App\Domain\Factory\ConnectionFactory as ConnectionFactoryInterface;

class ConnectionFactory implements ConnectionFactoryInterface
{
    private CompanyRepository $repository;
    private string $databaseNamePrefix;

    public function __construct(
        CompanyRepository $repository,
        string $databaseNamePrefix
    )
    {
        $this->repository = $repository;
        $this->databaseNamePrefix = $databaseNamePrefix;
    }

    public function preloadSettings(string $namespace): void
    {
        if(empty($namespace)){
            return;
        }

        $company = $this->repository->findOneByNamespace($namespace);
        if(!$company){
            throw new CompanyNotFound($namespace);
        }

        $_ENV['CRM_DATABASE_URL'] = str_replace('tfg_example', "{$this->databaseNamePrefix}_{$namespace}", $_ENV['CRM_DATABASE_URL']);
    }
}