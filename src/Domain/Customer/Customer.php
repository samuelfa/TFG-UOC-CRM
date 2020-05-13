<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace App\Domain\Customer;

use App\Domain\Familiar\Familiar;
use App\Domain\User\User;

class Customer extends User
{
    /**
     * @var Familiar[]
     */
    private $familiars;

    public function getRoles(): array
    {
        return [
            'ROLE_USER'
        ];
    }

    /**
     * @return \Generator|Familiar[]
     */
    public function familiars(): \Generator
    {
        foreach ($this->familiars as $familiar) {
            yield $familiar;
        }
    }
}
