<?php


namespace App\Infrastructure\Symfony\Event\FamiliarSendEmail;

use App\Domain\Event\Event as EventDomain;
use App\Domain\Familiar\Action\Email;
use Symfony\Contracts\EventDispatcher\Event;

class EmailCreatedEvent extends Event implements EventDomain
{
    private Email $email;

    public function __construct(Email $email)
    {
        $this->email    = $email;
    }

    public function email(): Email
    {
        return $this->email;
    }
}