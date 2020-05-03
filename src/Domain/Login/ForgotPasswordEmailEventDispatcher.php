<?php


namespace App\Domain\Login;


interface ForgotPasswordEmailEventDispatcher
{
    public function created(ForgotPasswordEmail $token, bool $customer): void;
}