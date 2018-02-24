<?php
require_once __DIR__ . '/vendor/autoload.php';
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

try {
    $mysqlConnection = mysqli_connect("localhost", "root", "", "marrick"); // todo: create mysqli connection if you want to test
}
catch (\Exception $e) {

    $errorCode = uniqid();
    error_log(sprintf('[%s] %s error connecting to  database::: %s', __FILE__, 'index.php', $e->getCode()));
    echo "Error connecting to database";
    die();


}

try {
    $database = new \app\QueryLocalDBRequest($tables, $mysqlConnection);
}
catch (\Exception $e) {

    $errorCode = uniqid();
    error_log(sprintf('[%s] %s error setting query   ::: %s', __FILE__, $database->error, $e->getCode()));
    echo "Error connecting to database";
    die();
}
try {
    $result = $database->get($select, $where, $orderBy, null);
}
catch (\Exception $e) {

    $errorCode = uniqid();
    error_log(sprintf('[%s] %s :: error in QueryLocalDBRequest.php   ::: %s', __FILE__, $database->error, $e->getCode()));
    echo "Error Running Query";
    die();
}
//display result in json format
echo json_encode($result, \JSON_PRETTY_PRINT | \JSON_FORCE_OBJECT | \JSON_NUMERIC_CHECK | \JSON_PRESERVE_ZERO_FRACTION);

// todo: follow instructions in README.md
