<?php

class DbConnect 

{	

	/* Member Variables */



	var $host = ''; 

	var $user = ''; 

	var $password = ''; 

	var $database = ''; 

	var $persistent = false; 

	var $conn = NULL; 

	var $result = false; 

	var $error_reporting = false; 

	

	/* Constructor */



	function DbConnect ($dbhost,$dbuser,$dbpass,$dbname, $error_reporting=true, $persistent=false) {

		$this->host = $dbhost; 

		$this->user = $dbuser; 

        	$this->password =$dbpass; 

		$this->database = $dbname; 

        	$this->persistent = $persistent; 

        	$this->error_reporting = $error_reporting; 

	} 

	

	/* Member Functions */



	/* Open Database Connection */



	function open() { 

		/* Connecting to Db Host */

		if ($this->persistent) {

			$this->conn = mysql_pconnect($this->host, $this->user, $this->password); 

		} else {
			$this->conn=	mysqli_connect($this->host, $this->user, $this->password,$this->database); 
//			$this->conn = mysql_connect($this->host, $this->user, $this->password); 
//mysqli_select_db($this->conn,$this->database);
		  }             

                   
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit;
}
        	if (!$this->conn) {

			print "Cannot connect to Database Host: ". $this->host;

			return false; 

		} 

                   

	        /* Select DB */ 

		if (@!mysqli_select_db($this->conn,$this->database)) {

			print "Cannot select Database: ". $this->database;

			return false; 

		} 

		return true; 

	}  

	

	/* Close the connection */ 

               

	function close() {

		return (@mysqli_close($this->conn)); 

	} 

    

	/* Report Error */ 

                 

	function error() {

		if ($this->error_reporting) {

			return (mysqli_error($this->conn)) ; 

		}           

	} 

	

	/* Execute Query */



	function query($sql) {

		$this->result = mysqli_query($this->conn,$sql); 

		return($this->result != false);         

	} 
	
	
	function query2($sql) {

		$this->result = @mysqli_query($this->conn, $sql); 

		return($this->result != false);         

	} 


	

	/* Affected Rows for Updates & Deletes */



	function affected_rows() {

		return(@mysqli_affected_rows($this->conn, $this->conn)); 

	} 

	

	/* Total Rows returned by Query */



	function num_rows() {

		return(@mysqli_num_rows($this->result)); 

	} 
	
	
	function num_rows2($rss) {

		return(@mysqli_num_rows($rss)); 

	} 

	

	

	/* Fetch Resultset as Object */



	function fetch_object() {

		return(mysqli_fetch_object($this->result)); 

	} 
	
	function fetch_object2($rss) {

		return(mysqli_fetch_object($rss)); 

	} 
	
	function fetch_obj2($rss) {

		return(mysqli_fetch_object($rss)); 

	} 

	/* Fetch Resultset as Array */



	function fetch_array() {

		return(mysqli_fetch_array($this->result)); 

	} 

	function fetch_array2($rss) {

		return(mysqli_fetch_array($rss)); 

	} 


	/* Fetch Resultset as Associate Array only */



	function fetch_assoc() {

		return(@mysqli_fetch_assoc($this->result)); 

	}
	
	function fetch_assoc2($rss) {

		return(@mysqli_fetch_assoc($rss)); 

	}  
	/* Free Resultset */

	function free_result() {

		return(@mysqli_free_result($this->result)); 

	}  

	

	/* Free Resultset */

	function insert_id() {
		global $connNew;
		return(mysqli_insert_id($connNew)); 

	} 

} 
?>