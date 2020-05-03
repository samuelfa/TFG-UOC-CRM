<?php


namespace App\Application\Login\Customer;


use App\Application\DTO;
use App\Domain\ValueObject\Password;

class RestorePasswordDTO implements DTO
{
    private string $token;
    private Password $password;

    public function __construct(string $token, string $password)
    {
        $this->token    = $token;
        $this->password = Password::encode($password);
    }

    public function value(): string
    {
        return $this->token;
    }

    public function password(): Password
    {
        return $this->password;
    }
}