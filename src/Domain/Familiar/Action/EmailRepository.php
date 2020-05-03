<?php


namespace App\Domain\Familiar\Action;


interface EmailRepository extends ActionRepository
{
    public function save(Email $email): void;
    public function remove(Email $email): void;
}