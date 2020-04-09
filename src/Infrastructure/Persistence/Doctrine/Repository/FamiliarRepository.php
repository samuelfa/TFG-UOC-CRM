<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarRepository as FamiliarRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FamiliarRepository implements FamiliarRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Familiar $company): void
    {
        $this->entityManager->persist($company);
    }
}