<?php


namespace App\Infrastructure\Symfony\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CompanyCreatedEvent extends Event
{
    public const NAME = 'company.created';

    private string $namespace;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return string
     */
    public function namespace(): string
    {
        return $this->namespace;
    }
}