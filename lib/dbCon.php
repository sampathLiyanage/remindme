<?php

class DB_connection{
protected $con;
	
	//constructor - creates mysql connection
	public function __construct(){
			$this->con= new mysqli("localhost","root","","remind_me");
		
		// Check connection
		if (mysqli_connect_errno($this->con)){
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
			exit();
		}
	}
	
	//exit if prepare statement has failed
	public function prepareSqlStmt($stmtToPrepare){
	        $stmt=$this->con->prepare($stmtToPrepare);
		if (!$stmt){
			echo "Prepare failed: (" . $this->con->errno . ") " . $this->con->error;
			exit();
		}
		return $stmt;
	}

	//execute an sql statement
	public function executeStmt($stmt){
		//execute query
		$result=$stmt->execute();
		var_dump($result);
		//if the query changed the database
		if ($result==1){
			return true;
		}else{
			return false;
		}
	}
}

?>
