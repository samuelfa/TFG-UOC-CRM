<?php


namespace App\Infrastructure\Symfony\Event\ForgotPassword;


use App\Domain\Login\ForgotPasswordEmailEventDispatcher as ForgotPasswordEmailEventDispatcherInterface;
use App\Domain\Login\ForgotPasswordEmail;
use Psr\EventDispatcher\EventDispatcherInterface;

class ForgotPasswordEmailEventDispatcher implements ForgotPasswordEmailEventDispatcherInterface
{
    private EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function created(ForgotPasswordEmail $token, bool $customer): void
    {
        $this->dispatcher->dispatch(new ForgotPasswordEmailCreatedEvent($token, $customer));
    }
}