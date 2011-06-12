<?php
/*! \class mysqlConn clrclass.php "includes/clrclass.php"
 *  \brief This class connects and selects MysqlDB
 *
 *  The two functions in the class connects and then selects the appropriate data base.
 *  Variables given are expected to be in another file (.mysqlconnect.php)
 */

class mysqlConn
{

	/*! \fn Connect 
	*   \brief connects to the mysqldb given special params
	*   \param db_hostname hostname of mysql server
	*   \param db_username username for the server
	*   \param db_password secret password for the server
	*   \exception this is a connection error
	*   \return returns a connection handler
	*/
	public function Connect($db_hostname, $db_username, $db_password){
		$con = mysql_connect($db_hostname, $db_username, $db_password);

		if (!$con) {
			die('Could Not Connect!: ' . mysql_error());
		}
		return $con;
	}

	/*! \fn Select
	*   \brief selects the mysql db to be used 
	*   \param con this is the connection handle (presumably returned by Connect function)
	*   \param db_database the name of the database you want to use
	*   \exception this happens when there is db select error
	*   \return this function returns a 0 letting you know it was successful
	*/
	public function Select($con,$db_database){
		mysql_select_db($db_database, $con)
			or die("Unable to select database: " . mysql_error());
		return 0;
	}
}
