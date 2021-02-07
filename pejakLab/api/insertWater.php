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

        if (!$body) {
            $output["status"] = "ERROR";
            $output["description"] = "Body is empty";

        } else {

            $bodyDecode = json_decode($body, true);

            $year = isset($bodyDecode['year']) ? $bodyDecode['year'] : "NULL";
            $month = isset($bodyDecode['month']) ? $bodyDecode['month'] : "NULL";
            $cons_state_reported = isset($bodyDecode['cons_state_reported']) ? $bodyDecode['cons_state_reported'] : "NULL";
            $cons_state_first_flore = isset($bodyDecode['cons_state_first_flore']) ? $bodyDecode['cons_state_first_flore'] : "NULL";
            $cons_state_ground_flore = isset($bodyDecode['cons_state_ground_flore']) ? $bodyDecode['cons_state_ground_flore'] : "NULL";
            $cons_state_yard_house = isset($bodyDecode['cons_state_yard_house']) ? $bodyDecode['cons_state_yard_house'] : "NULL";
            $consumption_discrepancy = isset($bodyDecode['consumption_discrepancy']) ? $bodyDecode['consumption_discrepancy'] : "NULL";
            $bill_amount = isset($bodyDecode['bill_amount']) ? $bodyDecode['bill_amount'] : "NULL";
            $cons_state_bill = isset($bodyDecode['cons_state_bill']) ? $bodyDecode['cons_state_bill'] : "NULL";
            $amount_communicated = isset($bodyDecode['amount_communicated']) ? $bodyDecode['amount_communicated'] : "NULL";
            $received_external = isset($bodyDecode['received_external']) ? $bodyDecode['received_external'] : "NULL";
            $read_date = isset($bodyDecode['read_date']) ? $bodyDecode['read_date'] : "NULL";
            $bill_payed_date = isset($bodyDecode['bill_payed_date']) ? $bodyDecode['bill_payed_date'] : "NULL";
            $consumption_first_flore = isset($bodyDecode['consumption_first_flore']) ? $bodyDecode['consumption_first_flore'] : "NULL";

            $query = "INSERT INTO water
                  (year, month, cons_state_reported, cons_state_first_flore, cons_state_ground_flore, cons_state_yard_house, 
                   consumption_discrepancy, bill_amount, cons_state_bill, amount_communicated, received_external, read_date, bill_payed_date, consumption_first_flore) 
                   VALUES ($year, $month, $cons_state_reported, $cons_state_first_flore, $cons_state_ground_flore, $cons_state_yard_house,
                    $consumption_discrepancy, $bill_amount, $cons_state_bill, $amount_communicated, $received_external, $read_date, $bill_payed_date, $consumption_first_flore) 
                    RETURNING id,year,month,cons_state_reported;";

            $insert = @pg_query($query) or die("Insert Fail >>>> ".pg_last_error());

            $output["insert_status"] = pg_result_status($insert);
            $insert_data = pg_fetch_row($insert);

            $output["data"] = isset($insert_data) ? $insert_data : "NULL";
        }

    } else {
        $output["status"] = "Unsupported";
        $output["description"] = "unsupported method";
    }

} else {
    $output["status"] = "Unauthorized";
    $output["description"] = "Authorization failed";
}

pg_close();

echo json_encode($output);