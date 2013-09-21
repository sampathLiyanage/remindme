<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/


/**
*all the classes those access the database
*should inherit from this class.
*
*@@todo: change the construction to connet to the database
*with different host, username and password to control access
*/ 
class DB_connection{
protected $con;
	
	//constructor - creates mysql connection
	public function __construct(){
			$this->con= new mysqli("localhost","root","","eventshare");
		
		// Check connection
		if (mysqli_connect_errno($this->con)){
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
			exit();
		}
	}
	
	/*
	*exit if prepare statement has failed
	*@input=> statement to prepare:sqli statement
	*@output=> prepaired statement:sqli prepaird statement
	*exits if prepair fails
	*/
	public function prepareSqlStmt($stmtToPrepare){
	        $stmt=$this->con->prepare($stmtToPrepare);
		if (!$stmt){
			echo "Prepare failed: (" . $this->con->errno . ") " . $this->con->error;
			exit();
		}
		return $stmt;
	}
	
	public function getSqlResults($stmt){
        	if (!$stmt->execute()){
        		return false;
        	}
        
        	$results=$stmt->get_result();
        
        	if ($results->num_rows==0){
        		return false;
        	}
        	return $results;
        }
}

?>
