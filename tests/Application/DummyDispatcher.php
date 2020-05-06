<?php


namespace Test\Application;


use Psr\EventDispatcher\EventDispatcherInterface;

class DummyDispatcher implements EventDispatcherInterface
{
    private int $total = 0;

    public function dispatch(object $event): void
    {
        $this->total++;
    }

    public function total(): int
    {
        return $this->total;
    }
}