<?php 

// read JSON data
$file_content = file_get_contents("/home/cs143/data/nobel-laureates.json");
$data = json_decode($file_content, true);

$current_directory = dirname(__FILE__);
$LaureatePerson = fopen($current_directory . "/LaureatePerson.del", "w");
$LaureateBirthDate = fopen($current_directory . "/LaureateBirthDate.del", "w");
$LaureateBirthPlace = fopen($current_directory . "/LaureateBirthPlace.del", "w");
$LaureateOrg = fopen($current_directory . "/LaureateOrg.del", "w");
$LaureateFoundedDate = fopen($current_directory . "/LaureateFoundedDate.del", "w");
$LaureateFoundedPlace = fopen($current_directory . "/LaureateFoundedPlace.del", "w");
$NobelPrizes = fopen($current_directory . "/NobelPrizes.del", "w");
//numnobels not needed bc we can count numbers by using query check on nobelprizes
$PrizeInfo = fopen($current_directory . "/PrizeInfo.del", "w");
$OneAffiliation = fopen($current_directory . "/OneAffiliation.del", "w");
$MultAffiliations = fopen($current_directory . "/MultAffiliations.del", "w");
$AffiliationsLocOne = fopen($current_directory . "/AffiliationsLocOne.del", "w");
$AffiliationsLocMult = fopen($current_directory . "/AffiliationsLocMult.del", "w");

// get the id, givenName, and familyName of the first laureate
for($i = 0; $i < sizeof($data["laureates"]); $i++) {       
       $laureate = $data["laureates"][$i];      
       $id = $laureate["id"];
       if(isset($laureate["givenName"])) {
          $givenName = $laureate["givenName"]["en"];
          $familyName = $laureate["familyName"]["en"];
	  $gender = $laureate["gender"];
	  $txt = "{$id}\t{$givenName}\t{$familyName}\t{$gender}\n";
	  fwrite($LaureatePerson, $txt);
	  
	  if(isset($laureate["birth"]["date"])) {
	     $birthDate = $laureate["birth"]["date"];
	     $txt = "{$id}\t{$birthDate}\n";	  
	     fwrite($LaureateBirthDate, $txt);
	  }
	  
	  if(isset($laureate["birth"]["place"]["city"]["en"]) || isset($laureate["birth"]["place"]["country"]["en"])) {
	     $birthCity = $laureate["birth"]["place"]["city"]["en"];
	     $birthCountry = $laureate["birth"]["place"]["country"]["en"];
	     $txt = "{$id}\t{$birthCity}\t{$birthCountry}\n";
	     fwrite($LaureateBirthPlace, $txt);	  
          }
       }
       
       else {
       	    $orgName = $laureate["orgName"]["en"];
	    $txt = "{$id}\t{$orgName}\n";
	    fwrite($LaureateOrg, $txt);	    

	    if(isset($laureate["founded"]["date"])) {
	       $foundedDate = $laureate["founded"]["date"];
	       $txt = "{$id}\t{$foundedDate}\n";
	       fwrite($LaureateFoundedDate, $txt);
	    }

            $txt2 = "{$id}\t"; fwrite($LaureateFoundedPlace, $txt2);
	    if(isset($laureate["founded"]["place"]["city"]["en"]) && !(empty($laureate["founded"]["place"]["city"]["en"]))) {
	       $foundedCity = $laureate["founded"]["place"]["city"]["en"];	     
	       $txt2 = "{$foundedCity}\t";
	       fwrite($LaureateFoundedPlace, $txt2);
            }
	    else if(!(isset($laureate["founded"]["place"]["city"]["en"])) || empty($laureate["founded"]["place"]["city"]["en"])) {
	       $txt2 = "\t";
	       fwrite($LaureateFoundedPlace, $txt2);
	    }
	
            if(isset($laureate["founded"]["place"]["country"]["en"]) && !(empty($laureate["founded"]["place"]["country"]["en"]))) {
	       $foundedCountry = $laureate["founded"]["place"]["country"]["en"];
	       $txt2 = "{$foundedCountry}\t"; fwrite($LaureateFoundedPlace, $txt2);
            }
	    $txt2 = "\n"; fwrite($LaureateFoundedPlace, $txt2);

       }

       
       //Max nobel prize is 3 for a person
       $txt = "{$id}\t";
       fwrite($NobelPrizes, $txt);
       for($j = 0;$j < sizeof($laureate["nobelPrizes"]); $j++) {	  
          $awardYear = $laureate["nobelPrizes"][$j]["awardYear"];       
          $category = $laureate["nobelPrizes"][$j]["category"]["en"];
          $sortOrder = $laureate["nobelPrizes"][$j]["sortOrder"];
          $prizeKey = $awardYear . $category . $sortOrder;
	  $txt = "{$prizeKey}\t";	  
	  fwrite($NobelPrizes, $txt);
	  
       	  $info = "{$prizeKey}\t{$awardYear}\t{$category}\t{$sortOrder}\n";
       	  fwrite($PrizeInfo, $info);

 	  if(sizeof($laureate["nobelPrizes"][$j]["affiliations"]) == 1) {
	     $affiliationName = $laureate["nobelPrizes"][$j]["affiliations"][0]["name"]["en"];
	     $affsOne = "{$prizeKey}\t{$affiliationName}\n";
	     fwrite($OneAffiliation, $affsOne);

	     $affiliationCity = $laureate["nobelPrizes"][$j]["affiliations"][0]["city"]["en"];
	     $affiliationCountry = $laureate["nobelPrizes"][$j]["affiliations"][0]["country"]["en"];
	     $affsOne = "{$prizeKey}\t{$affiliationCity}\t{$affiliationCountry}\n";
	     fwrite($AffiliationsLocOne, $affsOne);
	  }	
	  else if(sizeof($laureate["nobelPrizes"][$j]["affiliations"]) > 1) {
	     $affsMult = "{$prizeKey}\t";
	     $affsMultLoc = "{$prizeKey}\t";	     
	     fwrite($MultAffiliations, $affsMult);
	     fwrite($AffiliationsLocMult, $affsMultLoc);
	     
	     for($w = 0; $w < sizeof($laureate["nobelPrizes"][$j]["affiliations"]); $w++) {
		$affiliationName = $laureate["nobelPrizes"][$j]["affiliations"][$w]["name"]["en"];
		$affsMult = "{$affiliationName}\t";
		fwrite($MultAffiliations, $affsMult);

		$affiliationCity = $laureate["nobelPrizes"][$j]["affiliations"][$w]["city"]["en"];
	        $affiliationCountry = $laureate["nobelPrizes"][$j]["affiliations"][$w]["country"]["en"];
	        $affsMultLoc = "{$affiliationCity}\t{$affiliationCountry}\t";
	        fwrite($AffiliationsLocMult, $affsMultLoc);
	     }
	     $affsMult = "\n";
	     fwrite($MultAffiliations, $affsMult);
	     $affsMultLoc = "\n";
	     fwrite($AffiliationsLocMult, $affsMultLoc);
	  }
       }
       $txt = "\n";
       fwrite($NobelPrizes, $txt);              	  
        
    }
    	 	  	  	 	  

fclose($LaureatePerson);
fclose($LaureateBirthDate);
fclose($LaureateBirthPlace);
fclose($LaureateOrg);
fclose($LaureateFoundedDate);
fclose($LaureateFoundedPlace);
fclose($NobelPrizes);
fclose($PrizeInfo);
fclose($OneAffiliation);
fclose($MultAffiliations);
fclose($AffiliationsLocOne);
fclose($AffiliationsLocMult);
?>
