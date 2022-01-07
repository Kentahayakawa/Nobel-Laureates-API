<?php 

// read JSON data
$file_content = file_get_contents("/home/cs143/data/nobel-laureates.json");
$data = json_decode($file_content, true);

$current_directory = dirname(__FILE__);
$Import = fopen($current_directory . "/laureates.import", "w");

for($i = 0; $i < sizeof($data["laureates"]); $i++) {
       	    $laureate = $data["laureates"][$i];	    	    	    	    
	    $newLaureate = json_encode($laureate);
	    fwrite($Import, $newLaureate);
	    fwrite($Import, "\n");
}
fclose($Import);

?>
