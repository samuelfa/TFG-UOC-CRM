<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Login\ForgotPasswordEmailRepository as ForgotPasswordEmailRepositoryInterface;
use App\Domain\Login\ForgotPasswordEmail;
use App\Domain\ValueObject\EmailAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ForgotPasswordEmailRepository extends ServiceEntityRepository implements ForgotPasswordEmailRepositoryInterface
{
    public function __construct(ManagerRegistry $em)
    {
        parent::__construct($em, ForgotPasswordEmail::class);
    }

    public function save(ForgotPasswordEmail $token): void
    {
        $this->_em->persist($token);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function remove(ForgotPasswordEmail $token): void
    {
        $this->_em->remove($token);
    }

    public function findOneByToken(string $token): ?ForgotPasswordEmail
    {
        /** @var null|ForgotPasswordEmail $entity */
        $entity = $this->findOneBy([
            'token' => $token
        ]);
        return $entity;
    }

    public function findOneByEmailAddress(EmailAddress $emailAddress): ?ForgotPasswordEmail
    {
        /** @var null|ForgotPasswordEmail $entity */
        $entity = $this->find((string) $emailAddress);
        return $entity;
    }
}