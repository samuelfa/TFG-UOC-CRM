<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Familiar\Action\Email;
use App\Domain\Familiar\Action\EmailRepository as EmailRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EmailRepository extends ServiceEntityRepository implements EmailRepositoryInterface
{
    public function __construct(ManagerRegistry $em)
    {
        parent::__construct($em, Email::class);
    }

    public function save(Email $email): void
    {
        $this->_em->persist($email);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function remove(Email $email): void
    {
        $this->_em->remove($email);
    }
}