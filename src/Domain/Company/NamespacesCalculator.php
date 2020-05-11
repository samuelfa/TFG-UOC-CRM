<?php


namespace App\Domain\Company;


class NamespacesCalculator
{
    private string $domain;

    public function __construct(string $domain)
    {
        $this->domain = $domain;
    }

    public function obtain(string $uri): string
    {
        $namespace = str_replace($this->domain, '', $uri);
        if(!empty($namespace)){
            $lastCharacter = $namespace[strlen($namespace) - 1];
            if($lastCharacter === '.'){
                $namespace = substr($namespace, 0, -1);
            }
        }

        if(
            empty($namespace)
            || $namespace === 'www'
            || filter_var($namespace, FILTER_VALIDATE_IP)
        ){
            return '';
        }

        return $namespace;
    }
}