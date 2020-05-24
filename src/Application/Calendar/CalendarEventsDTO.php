<?php


namespace App\Application\Calendar;


use App\Application\DTO;

class CalendarEventsDTO implements DTO
{
    private \DateTime $start;
    private \DateTime $end;

    public function __construct(string $start, string $end)
    {
        $this->start = new \DateTime($start);
        $this->end   = new \DateTime($end);
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