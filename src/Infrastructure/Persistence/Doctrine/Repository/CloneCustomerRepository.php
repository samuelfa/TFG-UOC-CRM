<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Company\CloneCustomerRepository as CloneCustomerRepositoryInterface;
use App\Domain\Manager\Manager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class CloneCustomerRepository implements CloneCustomerRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private string $rootFolder;
    private string $databaseNamePrefix;

    public function __construct(EntityManagerInterface $entityManager, string $rootFolder, string $databaseNamePrefix)
    {
        $this->entityManager = $entityManager;
        $this->rootFolder    = $rootFolder;
        $this->databaseNamePrefix = $databaseNamePrefix;
    }

    public function create(string $namespace, Manager $manager): void
    {
        $connection = $this->entityManager->getConnection();
        $connection->beginTransaction();

        $this->createDatabase($connection, $namespace);
        $this->createTables($connection, $namespace);
        $this->insertManager($connection, $namespace, $manager);

        $connection->commit();
    }

    private function createDatabase(Connection $connection, string $namespace): void
    {
        $databaseName = $this->obtainDatabaseName($namespace);
        $userPrivilegesSQL = <<<SQL
CREATE DATABASE {$databaseName};
CREATE USER 'user_{$namespace}'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON {$databaseName}.* TO 'user_{$namespace}'@'%';
SQL;

        $connection->exec($userPrivilegesSQL);
    }

    private function createTables(Connection $connection, string $namespace): void
    {
        $databaseName = $this->obtainDatabaseName($namespace);
        $initialStructure = file_get_contents("{$this->rootFolder}/database/preload/initial-structure.sql");

        $content = <<<SQL
use {$databaseName};
{$initialStructure}
SQL;

        $connection->exec($content);
    }

    private function obtainDatabaseName(string $namespace): string
    {
        return "{$this->databaseNamePrefix}_{$namespace}";
    }

    private function insertManager(Connection $connection, string $namespace, Manager $manager): void
    {
        $databaseName = $this->obtainDatabaseName($namespace);
        $metadata = $this->entityManager->getClassMetadata(Manager::class);
        $tableName = $metadata->getTableName();

        $values = [];
        $params = [];
        $types = [];
        foreach ($metadata->getFieldNames() as $field){
            $values[$field] = ":{$field}";
            $params[":{$field}"] = $metadata->getFieldValue($manager, $field);
            $types[] = $metadata->getTypeOfField($field);
        }

        if(!empty($metadata->discriminatorColumn)){
            $field = $metadata->discriminatorColumn['name'];
            $values[$field] = ":{$field}";
            $params[":{$field}"] = $metadata->discriminatorValue;
            $types[] = $metadata->discriminatorColumn['type'];
        }

        $queryBuilder = $connection->createQueryBuilder()
                                   ->insert("{$databaseName}.{$tableName}")
                                   ->values($values)
                                   ->setParameters($params, $types)
        ;
        $queryBuilder->execute();
    }
}