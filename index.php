<?php

// import phpCAS lib
include_once('CAS.php');

//phpCAS::setDebug();

// initialize phpCAS
phpCAS::client(CAS_VERSION_2_0,'sso.pdx.edu/cas',443,'');

// no SSL validation for the CAS server
phpCAS::setNoCasServerValidation();

// force CAS authentication
phpCAS::forceAuthentication();

//Get users in to an array from text file

$userfile = fopen("qtUsers.txt","r");
$tempString = fread($userfile, filesize('qtUsers.txt'));
$userArray = explode(',',$tempString);

//set username variable
$user = phpCAS::getUser();

// Check to see if users are in the Array
if (!in_array($user, $userArray)){
	header('Location: https://oit.pdx.edu/qt/notregistered.html');
}
?>
<!DOCTYPE XHTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>
Quick ticket V2
</title>
<link href="css/quickticket.css" rel="stylesheet" type="text/css" />
<link href="jquery-ui/themes/south-street/jquery-ui-1.8.13.custom.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="favicon.ico" type="image/x-icon"> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script language="javascript" type="text/javascript" src="js/quickticket.js"></script>
<script language="javascript" type="text/javascript" src="jquery-ui/jquery-1.5.1.js"></script>
<script language="javascript" type="text/javascript" src="jquery-ui/ui/jquery-ui-1.8.13.custom.js"></script>
<script>
$(document).ready(function(){
	$('#tabs-1 #odin').focus();
	$('#tabs-1 #odin').change(function() {
		sendOdin('tabs-1');
	});
	$('#tabs-2 #odin').change(function() {
		sendOdin('tabs-2');
	});
	$('#tablink-2').click(function() {
		$('#tabs-2 #odin').focus();
	});
	$('#tabs-1 #quickSubject').change(function() {
		setFormSubject('tabs-1');
	});
	$('#tabs-2 #quickSubject').change(function() {
		setFormSubject('tabs-2');
	});
	$('.user-data button').click(function() {
		alert($('body div #creator').val());
	});
});
	$(function() {
		$("#tabs").tabs();
	});

</script>
</head>
<body> 
<div id="creator" style="display: none"><?php echo phpCAS::getUser();?></div> 
<h1>Quick Ticket</h1><img id="psuLogo" src="images/psu_logo.gif"/>

<div id="tabs">
	<ul>
		<li><a id="tablink-1" href="#tabs-1">Regular Ticket</a></li>
		<li><a id="tablink-2" href="#tabs-2">Workbench Ticket</a></li>
	</ul>
<div id="tabs-1">
	<?php include_once('inc/regular_ticket.html');?>
</div>
<div id="tabs-2">
	<?php include_once('inc/wrkbnchForms.html');?>
</div>
</div>
</body>
</html>
