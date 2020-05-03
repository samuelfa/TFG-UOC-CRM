<?php


namespace App\Application\Familiar\Action\SendEmail;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Familiar\Action\Email;
use App\Domain\Familiar\Action\EmailEventDispatcher;
use App\Domain\Familiar\Action\EmailRepository;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\Familiar\FamiliarRepository;

class SendEmailService implements TransactionalService
{
    private FamiliarRepository $repository;
    private EmailRepository $emailRepository;
    private EmailEventDispatcher $dispatcher;

    public function __construct(
        FamiliarRepository $repository,
        EmailRepository $emailRepository,
        EmailEventDispatcher $dispatcher
    )
    {
        $this->repository      = $repository;
        $this->emailRepository = $emailRepository;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var SendEmailDTO $dto */
        $nif = $dto->nif();
        $subject = $dto->subject();
        $body = $dto->body();
        $recipients = $dto->recipients();

        $familiar = $this->repository->findOneByNif($nif);
        if(!$familiar){
            throw new FamiliarNotFound($nif);
        }

        $email = Email::create(
            $subject,
            $body,
            $recipients,
            $familiar
        );

        $this->emailRepository->save($email);

        $this->dispatcher->created($email);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return SendEmailDTO::class;
    }
}