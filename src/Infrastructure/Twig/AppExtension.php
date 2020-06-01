<?php


namespace App\Infrastructure\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigTest;

class AppExtension extends AbstractExtension
{
    public function getTests(): array
    {
        return [
            new TwigTest('instanceof', [$this, 'isInstanceof'])
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('toString', [$this, 'toString']),
            new TwigFilter('flatten', [$this, 'flatten']),
        ];
    }

    public function isInstanceof(object  $var, string $instance): bool
    {
        return $var instanceof $instance;
    }

    public function toString($value, ?string $default = ''): string
    {
        if(empty($value)){
            return (string) $default;
        }

        return (string) $value[0];
    }

    public function flatten(array $list): array
    {
        $values = [];
        foreach ($list as $value){
            if(empty($value)){
                continue;
            }

            $values = [...$values, ...$value];
        }

        return $values;
    }
}