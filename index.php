<?php
require_once __DIR__.'/vendor/autoload.php';

$tables = [];
$mysqlConnection = null; // todo: create mysqli connection if you want to test

$database = new \app\QueryLocalDBRequest($tables, $mysqlConnection);

// todo: follow instructions in README.md
// when you are done, submit your code by zipping the project and sending it in an email as an attachment