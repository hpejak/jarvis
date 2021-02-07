<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$apiUsername = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
$apiPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;

$output = array("status" => "Init", "data" => array(), "timestamp" => time());
$dataResult = array();
$dataKeys = array();
$dataArray = array();
$dataLoopCount = 0;

if ($apiUsername == 'demoUsername' && $apiPassword == 'demoPassword') {

    require 'waterConnection.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $output["status"] = "OK";
        $output["description"] = "Nothing to report";
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $task = null;

        if ($getData = $_SERVER['QUERY_STRING']) {
            $output["fetQuery"] = $_GET;
            $task = $_GET['task'];
        }

        if ($task == 'getLastInput') {
            $query = 'SELECT *
                    FROM water
                    WHERE year IS NOT null
                    AND month IS NOT null
                    ORDER BY year DESC, month DESC, inserted_time DESC 
                    LIMIT 1';
        } else {
            $query = 'SELECT * FROM water';
        }


        $result = pg_query($query) or die('Query failed: ' . pg_last_error());

        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            array_push($dataArray, $line);
        }

        $output["data"] = $dataArray;

        $output["status"] = "OK";
        $output["description"] = "Nothing to report";
    }
} else {
    $output["status"] = "Unauthorized";
    $output["description"] = "Authorization failed";
}

pg_close();

echo json_encode($output);