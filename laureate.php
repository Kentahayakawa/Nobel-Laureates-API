    
    <?php     
     $db = new mysqli('localhost', 'cs143', '', 'class_db');
     
     // get the id parameter from the request
     $id = intval($_GET['id']);     
     
     if($id == "") {
     echo "Welcome to the Nobel Laureates Database! Please enter an ID.";
     }
     
     else {                   
     // set the Content-Type header to JSON, 
     // so that the client knows that we are returning JSON data       
     header('Content-Type: application/json');
     $query = "select givenName, familyName, gender from LaureatePerson where id=$id";
     $query2 = "select orgName from LaureateOrg where id=$id";
     
     $person = $db->query($query);
     $org = $db->query($query2);
    
        
     while($people = $person->fetch_assoc()) {
          $givenName = $people['givenName'];
          $familyName = $people['familyName'];
          $gender = $people['gender'];
     }
     $person->free();    

     while($orgs = $org->fetch_assoc()) {
          $orgName = $orgs['orgName'];
     }
     $org->free();
     
     if(!(empty($givenName)) && empty($orgName)) { 
          $query3 = "select birthDate from LaureateBirthDate where id=$id";
          $query4 = "select birthCity, birthCountry from LaureateBirthPlace where id=$id";
          $q3 = $db->query($query3);
          $q4 = $db->query($query4);
      
          while($bDay = $q3->fetch_assoc()) {
               $birthDate = $bDay['birthDate'];
          }
          while($bPlace = $q4->fetch_assoc()) {
               $birthCity = $bPlace['birthCity'];
               $birthCountry = $bPlace['birthCountry'];
          }

          $q3->free();
          $q4->free();    
            
          $output = (object) [
               "id" => strval($id),
               "givenName" => (object) [
                    "en" => strval($givenName)
               ],
               "familyName" => (object) [
                    "en" => strval($familyName)
               ], 
               "gender" => strval($gender),
               "birth" => (object) [
                    "date" => strval($birthDate),
                    "place" => (object) [
                         "city" => (object) ["en" => strval($birthCity)],
                         "country" => (object) ["en" => strval($birthCountry)]
                    ]
               ]
          ];
     }
     else if(!(empty($orgName)) && empty($givenName)) { 
          $query3 = "select foundedDate from LaureateFoundedDate where id=$id";
          $query4 = "select foundedCity, foundedCountry from LaureateFoundedPlace where id=$id";
          $q3 = $db->query($query3);
          $q4 = $db->query($query4); 
      
          while($fDay = $q3->fetch_assoc()) {
               $foundedDate = $fDay['foundedDate'];
          }
                  
          while($fPlace = $q4->fetch_assoc()) {
               $foundedCity = $fPlace['foundedCity'];
               $foundedCountry = $fPlace['foundedCountry'];
          }
          $q3->free();
          $q4->free();   
          if(!(empty($foundedDate)) && empty($foundedCity) && empty($foundedCountry)) {	              
           $output = (object) [
               "id" => strval($id),
               "orgName" => (object) [
                    "en" => strval($orgName)
               ],
	       "founded" => (object) [
                    "date" => strval($foundedDate)
               ]
           ];            
	  }
	  else if(!(empty($foundedDate)) && !(empty($foundedCity)) && !(empty($foundedCountry))) {
          $output = (object) [
               "id" => strval($id),
               "orgName" => (object) [
                    "en" => strval($orgName)
               ],
               "founded" => (object) [
                    "date" => strval($foundedDate),
                    "place" => (object) [
                         "city" => (object) ["en" => strval($foundedCity)],
                         "country" => (object) ["en" => strval($foundedCountry)]
                    ]
               ]
           ];
           }
	   else if(!(empty($foundedDate) && empty($foundedCity) && !(empty($foundedCountry)))) {
           $output = (object) [
               "id" => strval($id),
               "orgName" => (object) [
                    "en" => strval($orgName)
               ],
               "founded" => (object) [
                    "date" => strval($foundedDate),
                    "place" => (object) [
                         "country" => (object) ["en" => strval($foundedCountry)]
                    ]
               ]
           ];
           }
	   else if(!(empty($foundedDate)) && !(empty($foundedCity)) && empty($foundedCountry)) {
          $output = (object) [
               "id" => strval($id),
               "orgName" => (object) [
                    "en" => strval($orgName)
               ],
               "founded" => (object) [
                    "date" => strval($foundedDate),
                    "place" => (object) [
                         "city" => (object) ["en" => strval($foundedCity)]
                    ]
               ]
           ];
           }
	   
    }
    

    $query5 = "select awardYear, category, sortOrder  
               from PrizeInfo P, NobelPrizes N 
	       where $id=N.id and N.prizeKey=P.prizeKey";
    $q5 = $db->query($query5);
    $query6 = "select awardYear, category, sortOrder 
               from PrizeInfo P, NobelPrizes N  
	       where $id=N.id and N.prizeKey2=P.prizeKey";
    $q6 = $db->query($query6);
    $query7 = "select awardYear, category, sortOrder   
               from PrizeInfo P, NobelPrizes N
	       where $id=N.id and N.prizeKey3=P.prizeKey";
    $q7 = $db->query($query7);

    //3prizecase
    if(!(empty($q7)) && !(empty($q6)) && !(empty($q5))) {          
          while($rs5 = $q5->fetch_assoc()) {  
               $award1 = $rs5['awardYear'];
               $cat1 = $rs5['category'];
               $sort1 = $rs5['sortOrder'];
          }
    while($rs6 = $q6->fetch_assoc()) {
    
               $award2 = $rs6['awardYear'];
               $cat2 = $rs6['category'];
               $sort2 = $rs6['sortOrder'];
    }
    while($rs7=$q7->fetch_assoc()) {
               $award3 = $rs7['awardYear'];
               $cat3 = $rs7['category'];
               $sort3 = $rs7['sortOrder'];
          }
          $q5->free();
          $q6->free();
          $q7->free(); 
          if($award1) {
          $acs1 = (object) [
               "awardYear" => strval($award1),
               "category" => (object) [
	            "en" => strval($cat1)
	       ],
               "sortOrder" => strval($sort1)
          ]; }  
          if($award2) {
          $acs2 = (object) [
               "awardYear" => strval($award2),
               "category" => (object) [
	            "en" => strval($cat2)
	       ],
               "sortOrder" => strval($sort2)
          ];} 
          if($award3) {
          $acs3 = (object) [
               "awardYear" => strval($award3),
               "category" => (object) [
	            "en" => strval($cat3)
	       ],
               "sortOrder" => strval($sort3)
          ]; }
          


           //start affiliations for first prize
           $threePrizeQuery1 = "select affName 
                              from OneAffiliation O, NobelPrizes N
          		      where $id=N.id and N.prizeKey = O.prizeKey";        
           $threePrizeQuery2 = "select affName, affName2, affName3, affName4
	          	      from MultAffiliations M, NobelPrizes N
  	  	              where $id=N.id and N.prizeKey = M.prizeKey";
           $threePrizeQuery3 = "select affCity, affCountry 
	 	              from AffiliationsLocOne O, NobelPrizes N
            	 	      where $id=N.id and N.prizeKey = O.prizeKey";    
           $threePrizeQuery4 = "select affCity, affCountry, affCity2, affCountry2, affCity3, affCountry3, affCity4, affCountry4
         	              from AffiliationsLocMult M, NobelPrizes N
		              where $id=N.id and N.prizeKey = M.prizeKey";
    
           $thpq1 = $db->query($threePrizeQuery1);
           $thpq2 = $db->query($threePrizeQuery2);
           $thpq3 = $db->query($threePrizeQuery3);
           $thpq4 = $db->query($threePrizeQuery4);    
    
           while($x1 = $thpq1->fetch_assoc()) {
                $affName = $x1['affName'];
           }
           if(empty($affName)) {goto enda;}
   
           while($x3 = $thpq3->fetch_assoc()) {
                $affCity = $x3['affCity'];
                $affCountry = $x3['affCountry'];
           }
           $thpq1->free();
           $thpq3->free();
    
           $ncc = (object) [      
                "name" => (object) [
                     "en" => strval($affName)
                ],
                "city" => (object) [
                     "en" => strval($affCity)
                ],
                "country" => (object) [
                     "en" => strval($affCountry)
                ]
           ];

           $temp = (object) [
               "affiliations" => array($ncc)
           ];
    
           $acsnccMerge1 = array_merge_recursive((array) $acs1, (array) $temp);
           enda:
    
           while($x2 = $thpq2->fetch_assoc()) {
                $affName = $x2['affName'];
                $affName2 = $x2['affName2'];    
                $affName3 = $x2['affName3'];
                $affName4 = $x2['affName4'];
           }
           if(empty($affName2)) {goto endb;}
                while($x4 = $thpq4->fetch_assoc()) {
                $affCity = $x4['affCity'];
                $affCountry = $x4['affCountry'];
                $affCity2 = $x4['affCity2'];
                $affCountry2 = $x4['affCountry2'];
                $affCity3 = $x4['affCity3'];
                $affCountry3 = $x4['affCountry3'];
                $affCity4 = $x4['affCity4'];
                $affCountry5 = $x4['affCountry4'];
           }
           $thpq2->free();
           $thpq4->free();

    
           $ncc = (object) [      
                "name" => (object) [
                     "en" => strval($affName)
                ],
                "city" => (object) [
                     "en" => strval($affCity)
                ],
                "country" => (object) [
                     "en" => strval($affCountry)
                ]
           ];

           $ncc2 = (object) [
                "name" => (object) [
                     "en" => strval($affName2)
                ],
                "city" => (object) [
                      "en" => strval($affCity2)
                 ],
                 "country" => (object) [
                      "en" => strval($affCountry2)
                 ]
           ];

           $ncc3 = (object) [
                 "name" => (object) [
                       "en" => strval($affName3)
                 ],
                 "city" => (object) [
                       "en" => strval($affCity3)
                 ],
                 "country" => (object) [
                       "en" => strval($affCountry3)
                 ]
           ];

           $ncc4 = (object) [
                 "name" => (object) [
                        "en" => strval($affName4)
                 ],
                 "city" => (object) [
                        "en" => strval($affCity4)
                 ],
                 "country" => (object) [
                        "en" => strval($affCountry4)
                 ]
           ];

           if(!(isset($affName3)) || empty($affName3)) {
                $nccMerge = (object) [
                     "affiliations" => array($ncc, $ncc2)
                ];    
           }

           else if(!(isset($affName4)) || empty($affName4)) {
                $nccMerge = (object) [
                     "affiliations" => array($ncc, $ncc2, $ncc3)
                ];
           } 

           else {
                $nccMerge = (object) [
                     "affiliations" => array($ncc, $ncc2, $ncc3, $ncc4)
                ];
           }
    
           $acsnccMerge1 = array_merge_recursive((array) $acs1, (array) $nccMerge);      
           endb:
    
          if(empty($acs2)) {goto endTwo;}
          //start affiliations for second prize
          $threePrizeQuery5 = "select affName 
                             from OneAffiliation O, NobelPrizes N
		             where $id=N.id and N.prizeKey2 = O.prizeKey";        
          $threePrizeQuery6 = "select affName, affName2, affName3, affName4
		             from MultAffiliations M, NobelPrizes N
		             where $id=N.id and N.prizeKey2 = M.prizeKey";
          $threePrizeQuery7 = "select affCity, affCountry 
	            	     from AffiliationsLocOne O, NobelPrizes N
		             where $id=N.id and N.prizeKey2 = O.prizeKey";    
          $threePrizeQuery8 = "select affCity, affCountry, affCity2, affCountry2, affCity3, affCountry3, affCity4, affCountry4
	 	             from AffiliationsLocMult M, NobelPrizes N
		             where $id=N.id and N.prizeKey2 = M.prizeKey";
          
          $thpq5 = $db->query($threePrizeQuery5);
          $thpq6 = $db->query($threePrizeQuery6);
          $thpq7 = $db->query($threePrizeQuery7);
          $thpq8 = $db->query($threePrizeQuery8);
    
    
          while($x5 = $thpq5->fetch_assoc()) {
               $affName = $x5['affName'];
          }
          if(empty($affName)) {goto endc;}

          while($x7 = $thpq7->fetch_assoc()) {
               $affCity = $x7['affCity'];
               $affCountry = $x7['affCountry'];
          }
          $thpq5->free();
          $thpq7->free();
    
          $ncc = (object) [      
               "name" => (object) [
                    "en" => strval($affName)
               ],
               "city" => (object) [
                    "en" => strval($affCity)
                ],
                "country" => (object) [
                     "en" => strval($affCountry)
                ]
           ];
           $temp = (object) [
                "affiliations" => array($ncc)
           ];
           $acsnccMerge2 = array_merge_recursive((array) $acs2, (array) $temp);    
           endc:
            
           while($x6 = $thpq6->fetch_assoc()) {
                $affName = $x6['affName'];
                $affName2 = $x6['affName2'];
                $affName3 = $x6['affName3'];
                $affName4 = $x6['affName4'];
           }
           if(empty($affName2)) {goto endd;}

           while($x8 = $thpq8->fetch_assoc()) {
                $affCity = $x8['affCity'];
                $affCountry = $x8['affCountry'];
                $affCity2 = $x8['affCity2'];
                $affCountry2 = $x8['affCountry2'];
                $affCity3 = $x8['affCity3'];
                $affCountry3 = $x8['affCountry3'];
                $affCity4 = $x8['affCity4'];
                $affCountry5 = $x8['affCountry4'];
           }
           $thpq6->free();
           $thpq8->free();
    
           $ncc = (object) [      
                "name" => (object) [
                     "en" => strval($affName)
                 ],
                 "city" => (object) [
                     "en" => strval($affCity)
                 ],
                 "country" => (object) [
                     "en" => strval($affCountry)
                 ]
            ];
            $ncc2 = (object) [
                 "name" => (object) [
                      "en" => strval($affName2)
                 ],
                 "city" => (object) [
                      "en" => strval($affCity2)
                 ],
                 "country" => (object) [
                      "en" => strval($affCountry2)
                 ]
            ];
            $ncc3 = (object) [
                 "name" => (object) [
                      "en" => strval($affName3)
                 ],
                 "city" => (object) [
                       "en" => strval($affCity3)
                 ],
                 "country" => (object) [
                       "en" => strval($affCountry3)
                 ]
            ];
            $ncc4 = (object) [
                 "name" => (object) [
                       "en" => strval($affName4)
                 ],
                 "city" => (object) [
                       "en" => strval($affCity4)
                 ],
                 "country" => (object) [
                       "en" => strval($affCountry4)
                 ]
            ];

            if(!(isset($affName3)) || empty($affName3)) {
                 $nccMerge = (object) [
                      "affiliations" => array($ncc, $ncc2)
                 ];    
            }
            else if(!(isset($affName4)) || empty($affName4)) {
                $nccMerge = (object) [
                     "affiliations" => array($ncc, $ncc2, $ncc3)
                ];
            }
            else {
                 $nccMerge = (object) [
                      "affiliations" => array($ncc, $ncc2, $ncc3, $ncc4)
                 ];
            }

            $acsnccMerge2 = array_merge_recursive((array) $acs2, (array) $nccMerge);
            endd:
            endTwo:

    //start affiliations for third prize

          if(empty($acs3)) {goto endThree;}
          $threePrizeQuery9 = "select affName 
                             from OneAffiliation O, NobelPrizes N
		             where $id=N.id and N.prizeKey2 = O.prizeKey";        
          $threePrizeQuery10 = "select affName, affName2, affName3, affName4
		             from MultAffiliations M, NobelPrizes N
		             where $id=N.id and N.prizeKey2 = M.prizeKey";
          $threePrizeQuery11 = "select affCity, affCountry 
	            	     from AffiliationsLocOne O, NobelPrizes N
		             where $id=N.id and N.prizeKey2 = O.prizeKey";    
          $threePrizeQuery12 = "select affCity, affCountry, affCity2, affCountry2, affCity3, affCountry3, affCity4, affCountry4
	 	             from AffiliationsLocMult M, NobelPrizes N
		             where $id=N.id and N.prizeKey2 = M.prizeKey";
          
          $thpq9 = $db->query($threePrizeQuery9);
          $thpq10 = $db->query($threePrizeQuery10);
          $thpq11 = $db->query($threePrizeQuery11);
          $thpq12 = $db->query($threePrizeQuery12);
    
    
          while($x9 = $thpq9->fetch_assoc()) {
               $affName = $x9['affName'];
          }
          if(empty($affName)) {goto ende;}

          while($x11 = $thpq11->fetch_assoc()) {
               $affCity = $x11['affCity'];
               $affCountry = $x11['affCountry'];
          }
          $thpq9->free();
          $thpq11->free();
    
          $ncc = (object) [      
               "name" => (object) [
                    "en" => strval($affName)
               ],
               "city" => (object) [
                    "en" => strval($affCity)
                ],
                "country" => (object) [
                     "en" => strval($affCountry)
                ]
           ];
           $temp = (object) [
                "affiliations" => array($ncc)
           ];
           $acsnccMerge3 = array_merge_recursive((array) $acs3, (array) $temp);    
           ende:
           
           while($x10 = $thpq10->fetch_assoc()) {
                $affName = $x10['affName'];
                $affName2 = $x10['affName2'];
                $affName3 = $x10['affName3'];
                $affName4 = $x10['affName4'];
           }
           if(empty($affName2)) {goto endf;}

           while($x12 = $thpq12->fetch_assoc()) {
                $affCity = $x12['affCity'];
                $affCountry = $x12['affCountry'];
                $affCity2 = $x12['affCity2'];
                $affCountry2 = $x12['affCountry2'];
                $affCity3 = $x12['affCity3'];
                $affCountry3 = $x12['affCountry3'];
                $affCity4 = $x12['affCity4'];
                $affCountry5 = $x12['affCountry4'];
           }
           $thpq10->free();
           $thpq12->free();
    
           $ncc = (object) [      
                "name" => (object) [
                     "en" => strval($affName)
                 ],
                 "city" => (object) [
                     "en" => strval($affCity)
                 ],
                 "country" => (object) [
                     "en" => strval($affCountry)
                 ]
            ];
            $ncc2 = (object) [
                 "name" => (object) [
                      "en" => strval($affName2)
                 ],
                 "city" => (object) [
                      "en" => strval($affCity2)
                 ],
                 "country" => (object) [
                      "en" => strval($affCountry2)
                 ]
            ];
            $ncc3 = (object) [
                 "name" => (object) [
                      "en" => strval($affName3)
                 ],
                 "city" => (object) [
                       "en" => strval($affCity3)
                 ],
                 "country" => (object) [
                       "en" => strval($affCountry3)
                 ]
            ];
            $ncc4 = (object) [
                 "name" => (object) [
                       "en" => strval($affName4)
                 ],
                 "city" => (object) [
                       "en" => strval($affCity4)
                 ],
                 "country" => (object) [
                       "en" => strval($affCountry4)
                 ]
            ];

            if(!(isset($affName3)) || empty($affName3)) {
                 $nccMerge = (object) [
                      "affiliations" => array($ncc, $ncc2)
                 ];    
            }
            else if(!(isset($affName4)) || empty($affName4)) {
                $nccMerge = (object) [
                     "affiliations" => array($ncc, $ncc2, $ncc3)
                ];
            }
            else {
                 $nccMerge = (object) [
                      "affiliations" => array($ncc, $ncc2, $ncc3, $ncc4)
                 ];
            }

            $acsnccMerge3 = array_merge_recursive((array) $acs3, (array) $nccMerge);
            endf:
            endThree:
    
            if(is_null($acsnccMerge1) && is_null($acsnccMerge2)) {       
                 if(is_null($acsnccMerge3)) {
                      if(!(empty($acs2)) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acs1, $acs2, $acs3)];}           
                      else if(!(empty($acs2)) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acs1, $acs2)];}
                      else if(empty($acs2) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acs1, $acs3)];}
                      else if(empty($acs2) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acs1)];} 
                 }
                 else if(!(is_null($acsnccMerge3))) {
                      if(!(empty($acs2)) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acs1, $acs2, $acsnccMerge3)];}           
                      else if(!(empty($acs2)) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acs1, $acs2)];}
                      else if(empty($acs2) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acs1, $acsnccMerge3)];}
                      else if(empty($acs2) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acs1)];} 
                 }
             }
            else if(is_null($acsnccMerge1) && !(is_null($acsnccMerge2))) {
                 if(is_null($acsnccMerge3)) {
                      if(!(empty($acs2)) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acs1, $acsnccMerge2, $acs3)];}           
                      else if(!(empty($acs2)) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acs1, $acsnccMerge2)];}
                      else if(empty($acs2) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acs1, $acs3)];}
                      else if(empty($acs2) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acs1)];}
                 }
                 else if(!(is_null($acsnccMerge3))) {
                      if(!(empty($acs2)) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acs1, $acs2nccMerge, $acsnccMerge3)];}           
                      else if(!(empty($acs2)) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acs1, $acsnccMerge2)];}
                      else if(empty($acs2) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acs1, $acsnccMerge3)];}
                      else if(empty($acs2) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acs1)];}
                 }
             }
                
             else if(!(is_null($acsnccMerge1)) && is_null($acsnccMerge2)) {       
                  if(is_null($acsnccMerge3)) {
                      if(!(empty($acs2)) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acsnccMerge1, $acs2, $acs3)];}           
                      else if(!(empty($acs2)) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acsnccMerge1, $acs2)];}
                      else if(empty($acs2) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acsnccMerge1, $acs3)];}
                      else if(empty($acs2) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acsnccMerge1)];}
                 }
                 else if(!(is_null($acsnccMerge3))) {
                      if(!(empty($acs2)) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acsnccMerge1, $acs2, $acsnccMerge3)];}           
                      else if(!(empty($acs2)) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acsnccMerge1, $acs2)];}
                      else if(empty($acs2) && !(empty($acs3))) {$affMerge = (object) ["noelPrizes" => array($acsnerge1, $acsnccMerge3)];}
                      else if(empty($acs2) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acs1)];}
                 }
             }
             else if(!(is_null($acsnccMerge1)) && !(is_null($acsnccMerge2))) {
                  if(is_null($acsnccMerge3)) {
                      if(!(empty($acs2)) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acsnccMerge1, $acsnccMerge2, $acs3)];}
                      else if(!(empty($acs2)) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acsnccMerge1, $acsnccMerge2)];}
                      else if(empty($acs2) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acsnccMerge1, $acs3)];}
                      else if(empty($acs2) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acsnccmMerge1)];}
                 }
                 else if(!(is_null($acsnccMerge3))) {
                      if(!(empty($acs2)) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acsnccMerge1, $acsnccMerge2, $acsnccMerge3)];}
                      else if(!(empty($acs2)) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acsnccMerge1, $acsnccMerge2)];}
                      else if(empty($acs2) && !(empty($acs3))) {$affMerge = (object) ["nobelPrizes" => array($acsnccMerge1, $acsnccMerge3)];}
                      else if(empty($acs2) && empty($acs3)) {$affMerge = (object) ["nobelPrizes" => array($acsnccmMerge1)];}
                 }
              }                        
    
              $output = array_merge_recursive((array) $output, (array) $affMerge);
       
              echo json_encode($output);
      } //end 3 prize case
                                        
    
    }//end else
    
    $db->close();    
    ?>

