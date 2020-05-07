<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Activity\Activity;
use App\Domain\Familiar\Action\LinkActivity;
use App\Domain\Familiar\Action\LinkActivityRepository as LinkActivityRepositoryInterface;
use App\Domain\Familiar\Familiar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LinkActivityRepository extends ServiceEntityRepository implements LinkActivityRepositoryInterface
{
    public function __construct(ManagerRegistry $em)
    {
        parent::__construct($em, LinkActivity::class);
    }

    public function save(LinkActivity $linkActivity): void
    {
        $this->_em->persist($linkActivity);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function remove(LinkActivity $linkLinkActivity): void
    {
        $this->_em->remove($linkLinkActivity);
    }

    /**
     * @return LinkActivity[]
     */
    public function findByFamiliar(Familiar $familiar): array
    {
        return $this->findBy([
            'familiar' => $familiar
        ]);
    }

    public function findOneById(int $id): ?LinkActivity
    {
        /** @var null|LinkActivity $entity */
        $entity = $this->find($id);
        return $entity;
    }

    public function findByFamiliarAndDates(Familiar $familiar, \DateTime $start, \DateTime $end): array
    {
        $queryBuilder = $this->createQueryBuilder('events');
        $queryBuilder
            ->select('activities')
            ->innerJoin(Activity::class, 'activities', 'activities.id = events.activity')
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