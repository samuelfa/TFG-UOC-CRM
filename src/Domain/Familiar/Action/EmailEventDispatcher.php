<?php


namespace App\Domain\Familiar\Action;


interface EmailEventDispatcher
{
    public function created(Email $email): void;
}