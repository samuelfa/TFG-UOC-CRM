<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Category\CategoryRepository as CategoryRepositoryInterface;
use App\Domain\Category\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    public function __construct(ManagerRegistry $em)
    {
        parent::__construct($em, Category::class);
    }

    public function save(Category $category): void
    {
        $this->_em->persist($category);
    }

    public function findOneByName(string $name): ?Category
    {
        /** @var null|Category $entity */
        $entity = $this->findOneBy([
            'name' => $name
        ]);
        return $entity;
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function remove(Category $category): void
    {
        $this->_em->remove($category);
    }

    public function findOneById(int $id): ?Category
    {
        /** @var null|Category $entity */
        $entity = $this->find($id);
        return $entity;
    }

    public function total(): int
    {
        return $this->count([]);
    }
}