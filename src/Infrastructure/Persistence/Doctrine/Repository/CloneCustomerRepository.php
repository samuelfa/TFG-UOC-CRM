<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Company\CloneCustomerRepository as CloneCustomerRepositoryInterface;
use App\Domain\Manager\Manager;
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
        $databaseName = "{$this->databaseNamePrefix}_{$namespace}";
        $connection = $this->entityManager->getConnection();
        $connection->beginTransaction();

        $userPrivilegesSQL = <<<SQL
CREATE DATABASE {$databaseName};
CREATE USER 'user_{$namespace}'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON {$databaseName}.* TO 'user_{$namespace}'@'%';
SQL;

        $connection->exec($userPrivilegesSQL);

        $initialStructure = file_get_contents("{$this->rootFolder}/database/preload/initial-structure.sql");

        $content = <<<SQL
use {$databaseName};
{$initialStructure}
SQL;

        $connection->exec($content);

        $metadata = $this->entityManager->getClassMetadata(Manager::class);
        $tableName = $metadata->getTableName();
        $fields = $metadata->getFieldNames();

        $values = [];
        $params = [];
        $types = [];
        foreach ($fields as $field){
            $values[$field] = ":{$field}";
            $params[":{$field}"] = $metadata->getFieldValue($manager, $field);
            $types[] = $metadata->getTypeOfField($field);
        }

        $queryBuilder = $connection->createQueryBuilder()
                                   ->insert("{$databaseName}.{$tableName}")
                                   ->values($values)
                                   ->setParameters($params, $types)
        ;
        $queryBuilder->execute();

        $connection->commit();
    }
}