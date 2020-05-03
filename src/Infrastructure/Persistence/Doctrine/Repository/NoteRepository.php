<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Familiar\Action\Note;
use App\Domain\Familiar\Action\NoteRepository as NoteRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NoteRepository extends ServiceEntityRepository implements NoteRepositoryInterface
{
    public function __construct(ManagerRegistry $em)
    {
        parent::__construct($em, Note::class);
    }

    public function save(Note $note): void
    {
        $this->_em->persist($note);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function remove(Note $note): void
    {
        $this->_em->remove($note);
    }
}