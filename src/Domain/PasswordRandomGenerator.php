<?php


namespace App\Domain;


class PasswordRandomGenerator
{
    public function generate(): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle($chars),0, 30);
    }
}