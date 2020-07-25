<?php

namespace App\Core;

class PDOFactory
{
    /**
     * @var array
     */
    private static $dbConfig;

    /**
     * @throws \Exception
     */
    public static function getMysqlConnexion(): \PDO
    {
        self::$dbConfig = self::getConfig();

        $db = new \PDO('mysql:host='.self::$dbConfig['db-host'].';dbname='.self::$dbConfig['db-name'], self::$dbConfig['db-user'], self::$dbConfig['db-password']);
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $db;
    }

    private static function getConfig()
    {
        return yaml_parse_file(__DIR__.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'config'.\DIRECTORY_SEPARATOR.'db-config.yaml');
    }
}
