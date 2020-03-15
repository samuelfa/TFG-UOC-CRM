<?php


namespace App\Application;


interface TransactionalService
{
    public function subscribeTo(): string;
    public function __invoke(DTO $dto): DTO;
}