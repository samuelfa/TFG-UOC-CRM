<?php


namespace App\Domain\Familiar\Action;


interface Action
{
    public function createdAt(): \DateTimeImmutable;
}