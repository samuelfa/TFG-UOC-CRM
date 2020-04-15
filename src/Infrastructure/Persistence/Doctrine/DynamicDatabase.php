<?php


namespace App\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;

class DynamicDatabase extends Connection
{
    public function changeDatabase(string $databaseName): void
    {
        $params = $this->getParams();
        if (isset($params['dbname']) && $params['dbname'] === $databaseName) {
            return;
        }

        if ($this->isConnected()) {
            $this->close();
        }

        if (isset($params['url'])) {
            $params['url'] = str_replace('tfg_example', $databaseName, $params['url']);
        }

        if (isset($params['dbname'])) {
            $params['dbname'] = $databaseName;
        }

        $this->__construct(
            $params,
            $this->_driver,
            $this->_config,
            $this->_eventManager
        );
    }


}