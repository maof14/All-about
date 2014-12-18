<?php
$dbpath = realpath(__DIR__ . '/../db/.htsqlite.db');
return [
    'dsn'     => "sqlite:" . $dbpath,
    // massa snack
    'verbose' => false,

];
