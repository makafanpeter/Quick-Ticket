<?php
require_once 'ldap-fetch.php';

if ($_GET['odin']){
    $ldap_info = ldap_fetch($_GET['odin'], array("cn","mail","telephoneNumber","roomNumber","ou"));

	$returnString = json_encode($ldap_info);
	print $returnString;
}else{
	print "";
}
?>
