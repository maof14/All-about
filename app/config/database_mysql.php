<?php

return [
    'dsn'     		  => "mysql:host=localhost;dbname=maof14;",
    'username'        => "root",
    'password'        => "",
    'driver_options'  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
    'table_prefix'    => "",
    'verbose' => false,
    'debug_connect' => 'true',
];