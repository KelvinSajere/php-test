<?php
require_once __DIR__.'/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
/*
['tablename1','joinColumn','tableName2']
*/
$tables  = array(
    'patients',
    'patientID',
    'links_patients_to_law_firms'
);
//columns for get
/*table.column so as to aviod ambigous sql error*/
$select  = array(
    'linkID',
    'patients.patientID',
    'patients.name',
    'links_patients_to_law_firms.lawFirmID'
);
$where   = array(
    'links_patients_to_law_firms.lawFirmID' => 1
);
$orderBy = array(
    'linkID'
);
$mysqlConnection = null; // todo: create mysqli connection if you want to test

$database = new \app\QueryLocalDBRequest($tables, $mysqlConnection);

// todo: follow instructions in README.md
