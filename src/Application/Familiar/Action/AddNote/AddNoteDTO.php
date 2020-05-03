<?php


namespace App\Application\Familiar\Action\AddNote;


use App\Application\DTO;
use App\Domain\User\User;
use App\Domain\ValueObject\NIF;

class AddNoteDTO implements DTO
{
    private NIF    $nif;
    private string $message;
    private bool   $private;
    private User   $user;

    public function __construct(string $nif, string $message, bool $private, User $user)
    {
        $this->nif       = new NIF($nif);
        $this->message   = $message;
        $this->private   = $private;
        $this->user = $user;
    }

    public function nif(): NIF
    {
        return $this->nif;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function isPrivate(): bool
    {
        return $this->private;
    }

    public function user(): User
    {
        return $this->user;
    }
}