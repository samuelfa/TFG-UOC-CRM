<?php


namespace App\Application\Familiar\Action\AddNote;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Employee\Employee;
use App\Domain\Familiar\Action\Note;
use App\Domain\Familiar\Action\NoteRepository;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\Familiar\FamiliarRepository;

class AddNoteService implements TransactionalService
{
    private FamiliarRepository $repository;
    private NoteRepository $noteRepository;

    public function __construct(
        FamiliarRepository $repository,
        NoteRepository $noteRepository
    )
    {
        $this->repository = $repository;
        $this->noteRepository = $noteRepository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var AddNoteDTO $dto */
        $nif = $dto->nif();
        $user = $dto->user();
        $private = $dto->isPrivate();
        $message = $dto->message();

        $familiar = $this->repository->findOneByNif($nif);
        if(!$familiar){
            throw new FamiliarNotFound($nif);
        }

        if(!$user instanceof Employee){
            $private = false;
        }

        $note = Note::create(
            $message,
            $private,
            $familiar
        );

        $this->noteRepository->save($note);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return AddNoteDTO::class;
    }
}