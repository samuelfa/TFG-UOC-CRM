<?php


namespace App\Application\Login;


class TokenNotFound extends \RuntimeException
{
    public function __construct(string $token)
    {
        parent::__construct("Token {$token} not found");
    }

}