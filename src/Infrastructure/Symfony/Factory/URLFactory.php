<?php


namespace App\Infrastructure\Symfony\Factory;


class URLFactory
{
    private string $domainTemplate;

    public function __construct(string $domainTemplate)
    {
        $this->domainTemplate = $domainTemplate;
    }

    public function generate(string $namespace): string
    {
        return str_replace('{namespace}', $namespace, $this->domainTemplate);
    }
}