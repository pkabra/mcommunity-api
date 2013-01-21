<?php

/*
Quick MCommunity API
For getting MCommunity data in a JSON format for whatever reason you need it.

Documentation for use available in README.md

Designed by Pratik Kabra
*/

//Turn off error warnings because ldap warns me when more than 350 results are returned.
//(The LDAP directory is restricted to 350 returns for anonymous users)
error_reporting(E_ERROR | E_PARSE);

//Include file that controls variables and connects to LDAP respectively.
require('includes/vars.php');
require('includes/ldap.php');

//This variable is used to convert search queries into an LDAP approved search query.
$ldapsearch = "(&";

//Pass through all query requests, check if they are valid choices and then add them to the ldapsearch.
foreach(array_keys($searchvars) as $var){
	if(in_array($var, array_keys($requests))){
		$ldapsearch .= "(";
		//Need to add back slash and dash into phone number
		if($var == "mobile" || $var == "phone") echo $ldapsearch .= $requests[$var]."=".phoneuntrim($searchvars[$var]);
		else $ldapsearch .= $requests[$var]."=".clean($searchvars[$var]);
		$ldapsearch .= ")";
	}
}
$ldapsearch .= ")";

//Run the ldap search and get results.
$search = ldap_search($ldap, "ou=People, dc=umich, dc=edu", $ldapsearch, $allentities);
$info = ldap_get_entries($ldap, $search);

//This variable will store all returned results.
$out = array();

//LDAP returns things in a very ugly format.
//I'm assuming it has some purpose but I can't figure it out so
//for now I'm going to change the organisation which is done here.
foreach($info as $data){
	//Temp var to hold data until it gets pushed onto the main store.
	$entry = array();
	foreach($allentities as $entity){
		if(array_key_exists($entity, $data)){
			//Postal addresses have '$' signs instead of line breaks so change them.
			if($entity == "umichpostaladdress") $entry[$returns[$entity]] =  str_replace(" $ ",", ", $data[$entity][0]);
			//Phone numbers come with backslashes and dashes so change them as well.
			elseif($entity == "telephonenumber") $entry[$returns[$entity]] =  str_replace(array("/","-"),"", $data[$entity][0]);
			elseif($entity == "mobile") $entry[$returns[$entity]] =  str_replace(array("/","-"),"", $data[$entity][0]);
			elseif($entity == "ou") $entry[$returns[$entity]] = convertmulti($data[$entity]);
			else $entry[$returns[$entity]] =  $data[$entity][0];
		}
	}
	//This statement skips any empty results.
	if(!empty($entry)) array_push($out, $entry);
}

$data = array("count" => $info["count"], "data" => $out);

//Finally output data.
//If the request is Ajax or xml then just output the JSON
//otherwise add a <pre> tag to make it look pretty in a browser.
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  echo json_encode($data, JSON_PRETTY_PRINT);
}
else{
	echo "<pre>";
	echo json_encode($data, JSON_PRETTY_PRINT);
	echo "</pre>";
}