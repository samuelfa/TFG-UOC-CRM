<?php


namespace App\Domain\User;


class UserNotFound extends \RuntimeException
{
    public function __construct(string $nif)
    {
        parent::__construct("User {$nif} not found");
    }

}