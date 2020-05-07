<?php


namespace App\Application\Familiar\Action\LinkActivity;


use App\Application\DTO;
use App\Domain\ValueObject\NIF;

class DeleteLinkActivityDTO implements DTO
{
    private NIF $nif;
    private int $id;

    public function __construct(string $nif, int $id)
    {
        $this->nif = new NIF($nif);
        $this->id = $id;
    }

    public function nif(): NIF
    {
        return $this->nif;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }
}