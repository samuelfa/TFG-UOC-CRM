<?php


namespace App\Infrastructure\Symfony\Event\FamiliarSendEmail;


use App\Domain\Familiar\Action\Email;
use App\Domain\Familiar\Action\EmailEventDispatcher as EmailEventDispatcherInterface;

use Psr\EventDispatcher\EventDispatcherInterface;

class EmailEventDispatcher implements EmailEventDispatcherInterface
{
    private EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function created(Email $email): void
    {
        $this->dispatcher->dispatch(new EmailCreatedEvent($email));
    }
}