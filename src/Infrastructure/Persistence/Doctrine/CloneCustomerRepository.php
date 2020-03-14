<?php


namespace App\Infrastructure\Persistence\Doctrine;


use App\Domain\Company\CloneCustomerRepository as CloneCustomerRepositoryInterface;
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

    public function create(string $namespace): void
    {
        $databaseName = "{$this->databaseNamePrefix}_{$namespace}";
        $connection = $this->entityManager->getConnection();

        $userPrivilegesSQL = <<<SQL
CREATE DATABASE {$databaseName};
CREATE USER 'user_{$namespace}'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON {$databaseName}.* TO 'user_{$namespace}'@'%';
SQL;
        $connection->exec($userPrivilegesSQL);

        $content = file_get_contents("{$this->rootFolder}/database/preload/initial-structure.sql");
        $connection->exec($content);
    }
}