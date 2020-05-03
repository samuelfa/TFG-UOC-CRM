<?php


namespace App\Application\Familiar\Action\LinkActivity;


use App\Application\DTO;
use App\Domain\ValueObject\NIF;

class LinkActivityDTO implements DTO
{
    private NIF $nif;
    private int $activity;

    public function __construct(string $nif, int $activity)
    {
        $this->nif      = new NIF($nif);
        $this->activity = $activity;
    }

    public function nif(): NIF
    {
        return $this->nif;
    }

    public function activity(): int
    {
        return $this->activity;
    }
}