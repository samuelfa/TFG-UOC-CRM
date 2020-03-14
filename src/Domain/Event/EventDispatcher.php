<?php


namespace App\Domain\Event;


interface EventDispatcher
{
    public function dispatch(Event $event);
}