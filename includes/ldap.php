<?php

/*
LDAP include
Used to connect to the Umich LDAP server.

Pretty boring really.

Errors are returned as json.
*/


$ldap = ldap_connect("ldap://ldap.umich.edu",389) or die('[{"error":"Could Not Connect to LDAP Server"}]');
if(!ldap_bind($ldap)) die('[{"error":"Could Not Connect to LDAP Server"}]');
