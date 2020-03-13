<?php


namespace App\Domain\Factory;

interface ConnectionFactory
{
    public function preloadSettings(string $namespace): void;
}