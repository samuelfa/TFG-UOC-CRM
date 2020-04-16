<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Company\CloneCustomerRepository as CloneCustomerRepositoryInterface;
use App\Domain\Employee\Manager;
use App\Infrastructure\Persistence\Doctrine\DynamicDatabase;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;

class CloneCustomerRepository implements CloneCustomerRepositoryInterface
{
    private ManagerRegistry $manager;
    private string $rootFolder;
    private string $databaseNamePrefix;

    public function __construct(
        ManagerRegistry $manager,
        string $rootFolder,
        string $databaseNamePrefix
    )
    {
        $this->manager            = $manager;
        $this->rootFolder         = $rootFolder;
        $this->databaseNamePrefix = $databaseNamePrefix;
    }

    public function create(string $namespace, Manager $manager): void
    {
        /** @var DynamicDatabase $connection */
        $connection = $this->manager->getConnection('crm');

        $this->createDatabase($connection, $namespace);
        $this->createTables($connection);
        $this->insertManager($manager);
    }

    private function createDatabase(DynamicDatabase $connection, string $namespace): void
    {
        $databaseName = $this->obtainDatabaseName($namespace);

        $userPrivilegesSQL = <<<SQL
CREATE DATABASE {$databaseName};
CREATE USER 'user_{$namespace}'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON {$databaseName}.* TO 'user_{$namespace}'@'%';
SQL;

        $connection->exec($userPrivilegesSQL);
        $connection->changeDatabase($databaseName);
    }

    private function createTables(Connection $connection): void
    {
        $initialStructure = file_get_contents("{$this->rootFolder}/database/preload/initial-structure.sql");
        $connection->exec($initialStructure);
    }

    private function obtainDatabaseName(string $namespace): string
    {
        return "{$this->databaseNamePrefix}_{$namespace}";
    }

    private function insertManager(Manager $manager): void
    {
        $doctrineManager = $this->manager->getManagerForClass(Manager::class);
        if(!$doctrineManager){
            throw new \RuntimeException('Not manager found for App\Domain\Manager class');
        }
        $doctrineManager->persist($manager);
        $doctrineManager->flush();
    }
}