<?php

/*
Variable include
Used to get all the variables and keeps arrays containing variable names.
*/

//Simple clean function just gets rid of bad things.
//(Don't know why anyone would want to inject anything bad, no data is modified in this program anyway)
function clean($str){
	return str_replace(array(" ","/","'",'"',"\\","\n","\r","\t","\0"), "", $str);
}

//Array stores attribute to attribute assignments.
//I changed some of the attribute names so when a request is made its easier for people to understand.
$requests = array("uniqname" => "uid",
				"firstname" => "givenName",
				"surname" => "sn",
				"address" => "postalAddress",
				"umichPostalAddress" => "umichPostalAddress",
				"mobile" => "mobile",
				"pager" => "pager",
				"telephoneNumber" => "telephoneNumber",
				"mail" => "mail");

//Array used in the LDAP search to let LDAP know what to return.
$allentities = array("uid","cn","givenname","sn","postaladdress","umichpostaladdress","mobile","pager","telephonenumber","mail");

//Variable stores all the data being searched for.
$searchvars = $_GET;

