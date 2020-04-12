<?php


namespace App\Application\Company\Create;


class AlreadyExistsNamespace extends \RuntimeException
{
    public function __construct(string $namespace)
    {
        parent::__construct(sprintf('The namespace  %s is already in use', $namespace));
    }

}