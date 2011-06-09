<?php
if (($_POST['odin'] && $_POST['email'] && $_POST['name']) != ''){
  
  require_once 'rt_api.php';
  //RT_Api::$debug = 1; // debug mode


	$ticket = new Ticket();
	$ticket->id = 'ticket/'.$_POST['status'];
	$ticket->Status = $_POST['status'];
	$ticket->Queue = $_POST['queue'];
	$ticket->Requestor = $_POST['name'] . " <" . $_POST['email'] . ">";
	$ticket->Subject = $_POST['subject'];
	if ( $_POST['queue'] == 'uss-helpdesk-workbench'){
		$ticket->setCustomField('BENCH_Backup', $_POST['BENCH_Backup']);
		$ticket->setCustomField('BENCH_Customer_Type', $_POST['BENCH_Customer_Type']);
		$ticket->setCustomField('BENCH_Machine_Type', $_POST['BENCH_Machine_Type']);
		$ticket->setCustomField('BENCH_Machine_Name', $_POST['BENCH_Machine_Name']);
		$ticket->setCustomField('BENCH_Stage', $_POST['BENCH_Stage']);
		$ticket->setCustomField('BENCH_Serial_Number', $_POST['BENCH_Serial_Number']);
	}
	//$ticket->setCustomField('HD-ESC', 'Escalated');
	$info = array (
		'Requestor'=>$ticket->Requestor,
		'Phone Number'=>$_POST['phone'],
		'Department'=>$_POST['dept'],
		'Room Number'=>$_POST['room'],
	);
	$ticket->setBody($ticket->Subject, $ticket->Requester, $info , "RTKW-".$_POST['creator'], $_POST['description']);
  
    //print_r($ticket); 

	$api = new RT_Api('formhelper', 'password');
	$ticketinfo = $api->createTicket($ticket);
	$id = $ticketinfo->id;
	
	if ( $_POST['refersTo']){
		$api->refersTo($id,$_POST['refersTo']);
	}
	echo "<a href=\"https://support.oit.pdx.edu/Ticket/Display.html?id=$id\">$id</a>";
}

else {
	echo 'Odin, Name, and Email are required fields';
}

?>
