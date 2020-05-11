<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Activity\ActivityRepository as ActivityRepositoryInterface;
use App\Domain\Activity\Activity;
use App\Domain\Category\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ActivityRepository extends ServiceEntityRepository implements ActivityRepositoryInterface
{
    public function __construct(ManagerRegistry $em)
    {
        parent::__construct($em, Activity::class);
    }

    public function save(Activity $activity): void
    {
        $this->_em->persist($activity);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function remove(Activity $activity): void
    {
        $this->_em->remove($activity);
    }

    public function findOneById(int $id): ?Activity
    {
        /** @var null|Activity $entity */
        $entity = $this->find($id);
        return $entity;
    }

    public function findByCategory(Category $category): array
    {
        return $this->findBy([
            'category' => $category
        ]);
    }
}