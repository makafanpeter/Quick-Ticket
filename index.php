<?php

// import phpCAS lib, and mysql connect files
include_once('CAS.php');
include_once('bin/db_connect.php');
include_once('/home/thath/.mysql_connect.php');

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

$admin_user = array();
$connect = new mysqlConn();

$con = $connect->Connect($host, $db_user, $pass);

$connect->Select($con,$db);
$query = "SELECT * FROM qt_admin_users";

$result = mysql_query($query);

if(!$result) {
	return;
}else{
	while($row = mysql_fetch_assoc($result)){
		$admin_user['username'] = $row['username'];
		$admin_user['f_name']   = $row['f_name'];
		$admin_user['l_name']   = $row['l_name'];
	}
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
	$('#tabs-1 .user-data button').click(function() {
		createTicket('tabs-1');	
	});
	$('#tabs-2 .user-data button').click(function() {
		createTicket('tabs-2');	
	});
});
	$(function() {
		$("#tabs").tabs();
	});

</script>
</head>
<body> 
<div id="creator" style="display: none"><?php echo $user;?></div> 
<h1>Quick Ticket</h1><img id="psuLogo" src="images/psu_logo.gif"/>

<div id="tabs">
	<ul>
		<li><a id="tablink-1" href="#tabs-1">Regular Ticket</a></li>
		<li><a id="tablink-2" href="#tabs-2">Workbench Ticket</a></li>
		<?php if (in_array($user,$admin_user)){ 
			echo '<li><a id="tablink-3" href="#tabs-3">Admin Menu</a></li>';
		}?>
	</ul>
<div id="tabs-1">
	<?php include_once('inc/regular_ticket.html');?>
</div>
<div id="tabs-2">
	<?php include_once('inc/wrkbnchForms.html');?>
</div>
<div id="tabs-3">
	<?php
		print_r($admin_user);
	?>
</div>	
</div>
</body>
</html>
