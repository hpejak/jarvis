<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$output = array("status" => "Init", "data" => array(), "timestamp"=>time());


$apiUsername = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
$apiPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;

if ($apiUsername == 'demoUsername' && $apiPassword == 'demoPassword') {



    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        require 'waterConnection.php';

        $output["status"] = "OK";
        $output["description"] = "Empty";

        $body =  file_get_contents('php://input');
        $bodyDecode = json_decode($body, true);

        $year = isset($bodyDecode['year']) ? $bodyDecode['year'] : "NULL";
        $month = isset($bodyDecode['month']) ? $bodyDecode['month'] : "NULL";
        $cons_state_reported = isset($bodyDecode['reported']) ? $bodyDecode['reported'] : "NULL";
        $cons_state_first_flore = isset($bodyDecode['cons_state_first_flore']) ? $bodyDecode['cons_state_first_flore'] : "NULL";
        $cons_state_ground_flore = isset($bodyDecode['cons_state_ground_flore']) ? $bodyDecode['cons_state_ground_flore'] : "NULL";
        $cons_state_yard_house = isset($bodyDecode['cons_state_yard_house']) ? $bodyDecode['cons_state_yard_house'] : "NULL";
        $cons_discrepancy = isset($bodyDecode['cons_discrepancy']) ? $bodyDecode['cons_discrepancy'] : "NULL";
        $bill_amount = isset($bodyDecode['bill_amount']) ? $bodyDecode['bill_amount'] : "NULL";
        $amount_communicated = isset($bodyDecode['amount_communicated']) ? $bodyDecode['amount_communicated'] : "NULL";
        $received_external = isset($bodyDecode['received_external']) ? $bodyDecode['received_external'] : "NULL";
        $read_date = isset($bodyDecode['read_date']) ? $bodyDecode['read_date'] : "NULL";

        $query = "INSERT INTO water
                  (year, month, cons_state_reported, cons_state_first_flore, cons_state_ground_flore, cons_state_yard_house, 
                   cons_discrepancy, bill_amount, amount_communicated, received_external, read_date) 
                   VALUES ($year, $month, $cons_state_reported, $cons_state_first_flore, $cons_state_ground_flore, $cons_state_yard_house,
                    $cons_discrepancy, $bill_amount, $amount_communicated, $received_external, $read_date);";

        // TODO Catch error to var and return it in json
        $insert = pg_query($query) or die('Query failed: '.pg_last_error());

        $output["insert"] = $insert;
        $output["cons_state_reported"] =$cons_state_reported;
        $output["data"] = isset($bodyDecode['test']) ? $bodyDecode['test'] : "NULL";

    } else {
        $output["status"] = "Unsupported";
        $output["description"] = "unsupported method";
    }

} else {
    $output["status"] = "Unauthorized";
    $output["description"] = "Authorization failed";
}


echo json_encode($output);