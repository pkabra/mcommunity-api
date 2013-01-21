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

//Used to add back slash and dash to phone numbers
//Slashes and dashes are added by UM. Phone number looks like 555/123-4567
//Has to be capable of detecting wild card requests and adapting to them.
function phoneuntrim($str){
	//If a wild card is found at the beginning
	if(substr($str,0,1) == "*"){
		//Split the number into its 3 parts.
		$parta = substr(str_replace(array("*"), "", $str), 0, 3);
		$partb = substr(str_replace(array("*"), "", $str), 3, 3);
		$partc = substr(str_replace(array("*"), "", $str), 6, 4);
		//If the wild card is also at the end.
		if(substr($str,-1,1) == "*"){
			//Output slashes and dashes depending on number of numbers along with wildcards at both ends.
			if(strlen(str_replace(array("*"), "", $str)) < 3) return "*".$str."*";
			elseif(strlen(str_replace(array("*"), "", $str)) < 6) return "*".$parta."/".$partb."*";
			elseif(strlen(str_replace(array("*"), "", $str)) < 10) return "*".$parta."/".$partb."-".$partc."*";
			else return $parta."/".$partb."-".$partc;
		}
		//Otherwise do the same but with the wildcard only at the front.
		if(strlen(str_replace(array("*"), "", $str)) < 3) return "*".$str;
		elseif(strlen(str_replace(array("*"), "", $str)) < 6) return "*".$parta."/".$partb;
		elseif(strlen(str_replace(array("*"), "", $str)) < 10) return "*".$parta."/".$partb."-".$partc;
		else return $parta."/".$partb."-".$partc;
	}
	else{
		//Otherwise if the wild card is at the back.
		//Do exactly the same thing except leave the wildcard as the last item.
		$parta = substr($str, 0, 3);
		$partb = substr($str, 3, 3);
		$partc = substr($str, 6, 4);
		if(strlen(str_replace(array("*"), "", $str)) < 3) return $str;
		elseif(strlen(str_replace(array("*"), "", $str)) < 6) return $parta."/".$partb;
		elseif(strlen(str_replace(array("*"), "", $str)) < 10) return $parta."/".$partb."-".$partc;
		return $parta."/".$partb."-".$partc;
	}
}

//Array stores attribute to attribute assignments.
//I changed some of the attribute names so when a request is made its easier for people to understand.
$requests = array("uniqname" => "uid",
				"firstname" => "givenName",
				"surname" => "sn",
				"address" => "umichHomePostalAddress",
				"workaddress" => "umichPostalAddress",
				"mobile" => "mobile",
				"phone" => "telephoneNumber",
				"mail" => "mail");

//Same as the requests attribute except reversed to simplify JSON return.
$returns = array("uid" => "uniqname",
				"cn" => "fullname",
				"givenname" => "firstname",
				"sn" => "surname",
				"umichhomepostaladdress" => "address",
				"umichpostaladdress" => "workaddress",
				"mobile" => "mobile",
				"telephonenumber" => "phone",
				"mail" => "mail");

//Array used in the LDAP search to let LDAP know what to return.
$allentities = array("uid","cn","givenname","sn","umichpostaladdress","umichHomePostalAddress","mobile","telephonenumber","mail");

//Variable stores all the data being searched for.
$searchvars = $_GET;

