<?php

namespace App\Application\Company\SignIn;

use App\Application\DTO;

final class SignInNamespaceDTO implements DTO
{
    private string $namespace;
    private string $uri;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }
}
