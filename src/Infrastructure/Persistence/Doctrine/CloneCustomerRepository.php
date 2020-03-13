<?php


namespace App\Infrastructure\Persistence\Doctrine;


use App\Domain\Company\CloneCustomerRepository as CloneCustomerRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class CloneCustomerRepository implements CloneCustomerRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private string $rootFolder;

    public function __construct(EntityManagerInterface $entityManager, string $rootFolder)
    {
        $this->entityManager = $entityManager;
        $this->rootFolder    = $rootFolder;
    }

    public function create(string $namespace): void
    {
        $connection = $this->entityManager->getConnection();
        $connection->executeQuery('CREATE DATABASE :data_basename', ['data_basename' => $namespace], ['data_basename' => \PDO::PARAM_STR]);

        $content = file_get_contents("{$this->rootFolder}/database/preload/initial-structure.sql");
        $connection->exec($content);
    }
}