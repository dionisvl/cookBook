<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;
use Doctrine\DBAL\Driver\PDOPgSql\Driver as PDOPgSqlDriver;

return [
//    'db' => [//А это настройки для sqlite
//        'driver' => 'Pdo',
//        'dsn'    => sprintf('sqlite:%s/data/zftutorial.db', realpath(getcwd())),
//    ],

//    'db' => [
//        'driver' => 'Pdo',//А это настройки для Mysql
//        'dsn'    => 'mysql:dbname=zend;host=127.0.0.1',
//        'driver_options' => [
//            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
//        ],
//        'username' => 'root',
//        'password' => '',
//    ],

    'db' => [
        'driver' => 'Pdo_Pgsql',//НАстройки для PGSQL
        'dsn'    => 'pgsql:dbname=zend;host=localhost;port=5432;user=rootpg;password=1',
    ],

    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => '127.0.0.1',
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'zend',
                ]
            ],
        ],
    ],
];
