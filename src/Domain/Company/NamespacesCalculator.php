<?php


namespace App\Domain\Company;


class NamespacesCalculator
{
    private string $domain;

    public function __construct(string $domain)
    {
        $this->domain = $domain;
    }

    public function obtain(string $uri): string
    {
        $namespace = str_replace($this->domain, '', $uri);
        if(empty($namespace) || $namespace === 'www'){
            return '';
        }

        $lastCharacter = $namespace[strlen($namespace) - 1];
        if($lastCharacter === '.'){
            return substr($namespace, 0, -1);
        }

        return $namespace;
    }
}