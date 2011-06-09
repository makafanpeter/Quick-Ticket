<?php
/**
 * API class to submit data into RT
 Things that have been modified and changed from the original
 
 added function "refersTo()" 
	this allows users to add a refers to ticket
 modified setBody() 
	needed to do this for the use of quick ticket
 */

class RT_Api {
	const URI = 'https://support.oit.pdx.edu/REST/1.0/'; // Default REST URI for RT
	const URI_DEV = 'https://support-stage.oit.pdx.edu/REST/1.0/'; // REST URI for RT stage
	const PATH_ADD = 'ticket/new/';

	
	public static $debug = 0; // Debug = 1 will use URI_DEV, 0 will use URI
	
	protected $_client;
	protected $_username;
	protected $_password;
	
	public function __construct($username, $password){
		
		include_once 'Zend/Http/Client.php';
			$this->_client = new Zend_Http_Client();
			$this->_username = $username;
			$this->_password = $password;
		
	}
	
	/**
	 * Create new ticket using the API then return the 
	 * ticket after populating the new ticket ID
	 * @param Ticket $ticket
	 * @return Ticket
	 */
	public function createTicket(Ticket $ticket)
	{
		$uri = $this->getUri() . RT_Api::PATH_ADD . '?' . $this->getAuthenticationParams();
		$this->_client->setUri($uri);
		$this->_client->setParameterPost('content', (string)$ticket);
		$response = $this->_client->request('POST');
		preg_match('/Ticket(.*)created/', $response->getBody(), $ticketId);
		
		if (!isset($ticketId[1]) || trim($ticketId[1]) == 'ticket/new') {
			$ticket->id = false;
			watchdog('debug', "Cannot create new ticket.\n\n DEBUG: {$response->getBody()} \n\n");
			drupal_mail('[OIT-Form]API Ticket Creation Error', 'thath@pdx.edu', '[OIT-Form]API Ticket Creation Error', print_r($ticket, true), 'debug@oit.pdx.edu');
			if(RT_Api::$debug){
				throw new RT_Api_Exception("Cannot create new ticket.\n\n DEBUG: {$response->getBody()} \n\n", 1);	
			}
		} else {
			$ticket->id = trim($ticketId[1]);			
		}
		
		if (RT_Api::$debug) {
			print_r($response->getBody());
		}
		
		return $ticket;
	}
	
	//this function sets the "Refers To" field in rt if you supply the ticket and the referred ticket
	public function refersTo($ticket,$referredTicket){
		$uri = $this->getUri() . 'ticket/' . $ticket . '/links' . '?' . $this->getAuthenticationParams();
		$this->_client->setUri($uri);
		$this->_client->setParameterPost('content', 'RefersTo: fsck.com-rt://pdx.edu/ticket/' . $referredTicket );
		$this->_client->request('POST');
	}
	
	protected function getAuthenticationParams()
	{
		return 'user='.$this->_username.'&pass='.$this->_password;
	}
	
	protected function getUri(){
		return (RT_Api::$debug) ? RT_Api::URI_DEV : RT_Api::URI;
	}
}

class Ticket {
	public $id;
	public $Queue;
	public $Requestor;
	public $Subject;
	public $Cc;
	public $AdminCc;
	public $Owner;
	public $Status;
	public $Priority;
	public $InitialPriority;
	public $FinalPriority;
	public $TimeEstimated;
	public $Starts;
	public $Due;
	public $Text;
	protected $_customFields = array();
	
	/**
	 * Set custom field for Ticket
	 * @param string $name
	 * @param string $value
	 */
	public function setCustomField($name, $value){
		$this->_customFields[$name] = $value;
	}
	
	/**
	 * Convert object to string
	 */
	public function __toString(){
		$data = array();
		$vars = get_object_vars($this);
		foreach ($vars as $key => $value){
			if ($key == '_customFields'){
				foreach ($this->_customFields as $cfkey => $cfvalue){
					$data[] = 'cf-' . $cfkey . ': ' . $cfvalue;
				}
			} else {
				$data[] = $key . ': ' . $value;				
			}
		}
		$string = implode("\n", $data);
		return $string;
	}
	public function setBody($subject, $requester, $info, $keywords, $extra='') {
		$strinfo = '';
		foreach($info as $k => $v) {
			// Fix new line character problem for the form
			$v = str_replace("\n", " \n ", $v);
			$strinfo .= $v ? " $k: $v\n" : " $k:\n"; //add any request specific data
		} 
			
		$keywords = is_array($keywords)? implode(" ", $keywords) : $keywords; //add however many keywords
		
		#format the email
		$requester_info = '';
		foreach($requester as $key => $value){
			// email and name will be put in different way
			$key = trim($key);
			$value = trim($value);
			if($key != 'name' || $key != 'email')
			// Fix new line character problem for the form
			$value = str_replace("\n", " \n ", $value);
			$requester_info .= " $key: $value \n";
		}
		
		// Fix new line character problem for the form
		$extra = str_replace("\n", " \n ", $extra);
		
		$body=" $requester_info ".
		" $strinfo ".
		" \n ".
		"Description: ".
		" \n ".
		" $extra ".
		" \n \n \n \n ".
		"Keywords: {$keywords}";
		
		$this->Text = $body;	
}
}
class RT_Api_Exception extends Exception {}
