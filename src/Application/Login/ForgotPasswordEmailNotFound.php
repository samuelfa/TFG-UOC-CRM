<?php


namespace App\Application\Login;


class ForgotPasswordEmailNotFound extends \RuntimeException
{
    public function __construct(string $token)
    {
        parent::__construct("Forgot password email {$token} not found");
    }

}