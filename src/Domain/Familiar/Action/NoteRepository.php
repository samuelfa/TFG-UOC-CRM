<?php


namespace App\Domain\Familiar\Action;

use App\Domain\Repository;

interface NoteRepository extends Repository
{
    public function save(Note $note): void;
    public function remove(Note $note): void;
}