<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations
     * and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to
     * use if no other is specified.
     */
    public string $defaultGroup = 'default';

    public array $default = [
        'DSN'      => '',
        'hostname' => '127.0.0.1',
        'username' => 'timesheet',
        'password' => 'y9nhzJQR3',
        'database' => 'timesheet',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => true,
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];

    /**
     * This database connection is used when
     * running PHPUnit database tests.
     */
    public array $tests = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => 'utf8_general_ci',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
        'foreignKeys' => true,
        'busyTimeout' => 1000,
    ];

    public function __construct()
    {
        parent::__construct();

        $host = getenv('MYSQL_HOST');
        $db = getenv('MYSQL_DB');
        $pass = getenv('MYSQL_PASS');
        $port = getenv('MYSQL_PORT');
        $user = getenv('MYSQL_USER');

        if (!empty($host)) $this->default['hostname'] = $host;
        if (!empty($db)) $this->default['database'] = $db;
        if (!empty($port)) $this->default['port'] = $port;
        if (!empty($pass)) $this->default['password'] = $pass;
        if (!empty($user)) $this->default['username'] = $user;

        #if (!empty($host) && !empty($db) && !empty($user))

        // // Ensure that we always set the database group to 'tests' if
        // // we are currently running an automated test suite, so that
        // // we don't overwrite live data on accident.
        // if (ENVIRONMENT === 'testing') {
        //     $this->defaultGroup = 'tests';
        // }
    }
}
