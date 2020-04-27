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
     * @return Familiar[]
     */
    public function familiars(): array
    {
        return $this->familiars;
    }
}
