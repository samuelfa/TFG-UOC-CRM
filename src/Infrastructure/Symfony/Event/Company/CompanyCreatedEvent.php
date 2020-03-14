<?php


namespace App\Infrastructure\Symfony\Event\Company;

use App\Domain\Event\Event as EventDomain;
use Symfony\Contracts\EventDispatcher\Event;

class CompanyCreatedEvent extends Event implements EventDomain
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