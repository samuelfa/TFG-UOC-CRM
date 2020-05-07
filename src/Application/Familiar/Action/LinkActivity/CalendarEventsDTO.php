<?php


namespace App\Application\Familiar\Action\LinkActivity;


use App\Application\DTO;
use App\Domain\ValueObject\NIF;

class CalendarEventsDTO implements DTO
{
    private NIF $nif;
    private \DateTime $start;
    private \DateTime $end;

    public function __construct(string $nif, string $start, string $end)
    {
        $this->nif   = new NIF($nif);
        $this->start = new \DateTime($start);
        $this->end   = new \DateTime($end);
    }

    public function nif(): NIF
    {
        return $this->nif;
    }

    public function start(): \DateTime
    {
        return $this->start;
    }

    public function end(): \DateTime
    {
        return $this->end;
    }
}