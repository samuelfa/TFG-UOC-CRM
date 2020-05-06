<?php


namespace App\Application\Login;


class ForgotPasswordEmailExpired extends \RuntimeException
{
    public function __construct(string $token)
    {
        parent::__construct("Forgot password email {$token} expired");
    }
}