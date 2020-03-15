<?php


namespace App\Domain\Person;


use App\Domain\ValueObject\URL;

class AbstractPerson
{
    protected string $nif;
    protected string $name;
    protected string $surname;
    protected \DateTimeInterface $birthday;
    protected URL $portrait;

    public function __construct(string $nif, string $name, string $surname, \DateTimeInterface $birthday, URL $portrait)
    {
        $this->nif = $nif;
        $this->name     = $name;
        $this->surname  = $surname;
        $this->birthday     = $birthday;
        $this->portrait = $portrait;
    }

    /**
     * @return string
     */
    public function nif(): string
    {
        return $this->nif;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function surname(): string
    {
        return $this->surname;
    }

    /**
     * @return \DateTimeInterface
     */
    public function birthday(): \DateTimeInterface
    {
        return $this->birthday;
    }

    /**
     * @return URL
     */
    public function portrait(): URL
    {
        return $this->portrait;
    }


}