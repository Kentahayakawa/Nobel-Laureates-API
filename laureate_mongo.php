<?php

error_reporting(E_ALL);
ini_set("display_errors", "1");
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");

$db = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// get the id parameter from the request
$id = intval($_GET['id']);

if($id=="") {
echo "Welcome to the Nobel Laureates Database! Please enter an ID.";
}
else {
// set the Content-Type header to JSON, 
// so that the client knows that we are returning JSON data
header('Content-Type: application/json');

$filter = [ 'id' => strval($id) ];
$options = ["projection" => ['_id' => 0]];
$query = new MongoDB\Driver\Query($filter, $options);
$rows = $db->executeQuery("nobel.laureates", $query);
foreach($rows as $row) {
echo (json_encode($row));
}

}
?>
