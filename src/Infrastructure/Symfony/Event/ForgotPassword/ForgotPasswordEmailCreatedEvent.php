<?php


namespace App\Infrastructure\Symfony\Event\ForgotPassword;

use App\Domain\Event\Event as EventDomain;
use App\Domain\Login\ForgotPasswordEmail;
use Symfony\Contracts\EventDispatcher\Event;

class ForgotPasswordEmailCreatedEvent extends Event implements EventDomain
{
    private ForgotPasswordEmail $token;
    private bool                $customer;

    public function __construct(ForgotPasswordEmail $token, bool $customer)
    {
        $this->token = $token;
        $this->customer = $customer;
    }

    public function token(): ForgotPasswordEmail
    {
        return $this->token;
    }

    public function isCustomer()
    {
        return $this->customer;
    }
}