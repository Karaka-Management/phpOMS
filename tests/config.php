<?php

return [
    'db' => [
        'core' => [
            'masters' => [
                'admin' => [
                    'db'             => 'mysql', /* db type */
                    'host'           => '127.0.0.1', /* db host address */
                    'port'           => '3306', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'insert' => [
                    'db'             => 'mysql', /* db type */
                    'host'           => '127.0.0.1', /* db host address */
                    'port'           => '3306', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'select' => [
                    'db'             => 'mysql', /* db type */
                    'host'           => '127.0.0.1', /* db host address */
                    'port'           => '3306', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'update' => [
                    'db'             => 'mysql', /* db type */
                    'host'           => '127.0.0.1', /* db host address */
                    'port'           => '3306', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'delete' => [
                    'db'             => 'mysql', /* db type */
                    'host'           => '127.0.0.1', /* db host address */
                    'port'           => '3306', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'schema' => [
                    'db'             => 'mysql', /* db type */
                    'host'           => '127.0.0.1', /* db host address */
                    'port'           => '3306', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
            ],
            'postgresql' => [
                'admin' => [
                    'db'             => 'pgsql', /* db type */
                    'host'           => '127.0.0.1', /* db host address */
                    'port'           => '5432', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'insert' => [
                    'db'             => 'pgsql', /* db type */
                    'host'           => '127.0.0.1', /* db host address */
                    'port'           => '5432', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'select' => [
                    'db'             => 'pgsql', /* db type */
                    'host'           => '127.0.0.1', /* db host address */
                    'port'           => '5432', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'update' => [
                    'db'             => 'pgsql', /* db type */
                    'host'           => '127.0.0.1', /* db host address */
                    'port'           => '5432', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'delete' => [
                    'db'             => 'pgsql', /* db type */
                    'host'           => '127.0.0.1', /* db host address */
                    'port'           => '5432', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'schema' => [
                    'db'             => 'pgsql', /* db type */
                    'host'           => '127.0.0.1', /* db host address */
                    'port'           => '5432', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
            ],
            'sqlite' => [
                'admin' => [
                    'db'             => 'sqlite', /* db type */
                    'database'       => __DIR__ . '/../Localization/Defaults/localization.sqlite', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'insert' => [
                    'db'             => 'sqlite', /* db type */
                    'database'       => __DIR__ . '/../Localization/Defaults/localization.sqlite', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'select' => [
                    'db'             => 'sqlite', /* db type */
                    'database'       => __DIR__ . '/../Localization/Defaults/localization.sqlite', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'update' => [
                    'db'             => 'sqlite', /* db type */
                    'database'       => __DIR__ . '/../Localization/Defaults/localization.sqlite', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'delete' => [
                    'db'             => 'sqlite', /* db type */
                    'database'       => __DIR__ . '/../Localization/Defaults/localization.sqlite', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'schema' => [
                    'db'             => 'sqlite', /* db type */
                    'database'       => __DIR__ . '/../Localization/Defaults/localization.sqlite', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
            ],
            'mssql' => [
                'admin' => [
                    'db'             => 'mssql', /* db type */
                    'host'           => 'localhost', /* db host address */
                    'port'           => '1433', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'insert' => [
                    'db'             => 'mssql', /* db type */
                    'host'           => 'localhost', /* db host address */
                    'port'           => '1433', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'select' => [
                    'db'             => 'mssql', /* db type */
                    'host'           => 'localhost', /* db host address */
                    'port'           => '1433', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'update' => [
                    'db'             => 'mssql', /* db type */
                    'host'           => 'localhost', /* db host address */
                    'port'           => '1433', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'delete' => [
                    'db'             => 'mssql', /* db type */
                    'host'           => 'localhost', /* db host address */
                    'port'           => '1433', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
                'schema' => [
                    'db'             => 'mssql', /* db type */
                    'host'           => 'localhost', /* db host address */
                    'port'           => '1433', /* db host port */
                    'login'          => 'test', /* db login name */
                    'password'       => 'orange', /* db login password */
                    'database'       => 'omt', /* db name */
                    'weight'         => 1000, /* db table prefix */
                    'datetimeformat' => 'Y-m-d H:i:s',
                ],
            ],
        ],
    ],
    'cache' => [
        'redis' => [
            'db'   => 1,
            'host' => '127.0.0.1',
            'port' => 6379,
        ],
        'memcached' => [
            'host' => '127.0.0.1',
            'port' => 11211,
        ],
    ],
    'mail' => [
        'imap' => [
            'host'     => '127.0.0.1',
            'port'     => 143,
            'ssl'      => false,
            'user'     => 'test',
            'password' => '123456',
        ],
        'pop3' => [
            'host'     => '127.0.0.1',
            'port'     => 25,
            'ssl'      => false,
            'user'     => 'test',
            'password' => '123456',
        ],
    ],
    'log' => [
        'file' => [
            'path' => __DIR__ . '/Logs',
        ],
    ],
    'page' => [
        'root'  => '/',
        'https' => false,
    ],
    'app' => [
        'path'    => __DIR__,
        'default' => [
            'app'   => 'Backend',
            'id'    => 'backend',
            'lang'  => 'en',
            'theme' => 'Backend',
            'org'   => 1,
        ],
        'domains' => [
            '127.0.0.1' => [
                'app'   => 'Backend',
                'id'    => 'backend',
                'lang'  => 'en',
                'theme' => 'Backend',
                'org'   => 1,
            ],
        ],
    ],
    'socket' => [
        'master' => [
            'host'  => '127.0.0.1',
            'limit' => 300,
            'port'  => 4310,
        ],
    ],
    'language' => [
        'en',
    ],
    'apis' => [
    ],
];