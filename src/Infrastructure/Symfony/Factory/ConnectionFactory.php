<?php


namespace App\Infrastructure\Symfony\Factory;


use App\Domain\Company\CompanyNotFound;
use App\Domain\Company\CompanyRepository;
use App\Domain\Factory\ConnectionFactory as ConnectionFactoryInterface;
use App\Infrastructure\Persistence\Doctrine\DynamicDatabase;
use Doctrine\Persistence\ManagerRegistry;

class ConnectionFactory implements ConnectionFactoryInterface
{
    private CompanyRepository $repository;
    private string $databaseNamePrefix;
    private ManagerRegistry $manager;

    public function __construct(
        CompanyRepository $repository,
        string $databaseNamePrefix,
        ManagerRegistry $manager
    )
    {
        $this->repository = $repository;
        $this->databaseNamePrefix = $databaseNamePrefix;
        $this->manager = $manager;
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

        $databaseName = $this->obtainDatabaseName($namespace);

        /** @var DynamicDatabase $connection */
        $connection = $this->manager->getConnection('crm');
        $connection->changeDatabase($databaseName);
    }

    private function obtainDatabaseName(string $namespace): string
    {
        return "{$this->databaseNamePrefix}_{$namespace}";
    }
}