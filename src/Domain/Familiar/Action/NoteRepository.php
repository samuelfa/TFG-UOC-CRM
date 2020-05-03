<?php


namespace App\Domain\Familiar\Action;


interface NoteRepository extends ActionRepository
{
    public function save(Note $note): void;
    public function remove(Note $note): void;
}