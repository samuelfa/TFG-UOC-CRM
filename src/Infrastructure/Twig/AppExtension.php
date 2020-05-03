<?php


namespace App\Infrastructure\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class AppExtension extends AbstractExtension
{
    public function getTests(): array
    {
        return [
            new TwigTest('instanceof', [$this, 'isInstanceof'])
        ];
    }

    public function isInstanceof(object  $var, string $instance): bool
    {
        return $var instanceof $instance;
    }
}