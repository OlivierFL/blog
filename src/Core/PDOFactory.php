<?php

namespace App\Core;

class PDOFactory
{
    /**
     * @var array
     */
    private $config;

    /**
     * PDOFactory constructor.
     */
    public function __construct()
    {
        $this->config = $this->getConfig();
    }

    /**
     * @return \PDO
     */
    public function getMysqlConnexion(): \PDO
    {
        $db = new \PDO('mysql:host='.$this->config['db-host'].';dbname='.$this->config['db-name'], $this->config['db-user'], $this->config['db-password']);
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $db;
    }

    /**
     * @return mixed
     */
    private function getConfig()
    {
        return yaml_parse_file(__DIR__.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'config'.\DIRECTORY_SEPARATOR.'db-config.yaml');
    }
}
