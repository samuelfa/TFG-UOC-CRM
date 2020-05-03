<?php


namespace App\Infrastructure\Symfony\Event\ForgotPassword;

use App\Domain\Event\Event as EventDomain;
use App\Domain\Login\ForgotPasswordEmail;
use Symfony\Contracts\EventDispatcher\Event;

class ForgotPasswordEmailCreatedEvent extends Event implements EventDomain
{
    private ForgotPasswordEmail $token;

    public function __construct(ForgotPasswordEmail $token)
    {
        $this->token = $token;
    }

    public function token(): ForgotPasswordEmail
    {
        return $this->token;
    }
}