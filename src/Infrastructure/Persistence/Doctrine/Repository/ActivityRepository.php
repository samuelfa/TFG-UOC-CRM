<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Activity\ActivityRepository as ActivityRepositoryInterface;
use App\Domain\Activity\Activity;
use App\Domain\Category\Category;
use App\Domain\Familiar\Action\LinkActivity;
use App\Domain\Familiar\Familiar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
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

    public function total(): int
    {
        return $this->count([]);
    }

    public function findByFamiliarAndDates(Familiar $familiar, \DateTime $start, \DateTime $end): array
    {
        $queryBuilder = $this->createQueryBuilder('activities');
        $queryBuilder
            ->select('activities')
            ->innerJoin(LinkActivity::class, 'events', Expr\Join::WITH, 'activities.id = events.activity')
            ->where('events.familiar = :familiar')
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->between('activities.startAt', ':start_filter', ':end_filter'),
                    $queryBuilder->expr()->between('activities.finishAt', ':start_filter', ':end_filter')
                )
            )
            ->setParameter('familiar', $familiar)
            ->setParameter('start_filter', $start)
            ->setParameter('end_filter', $end)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}