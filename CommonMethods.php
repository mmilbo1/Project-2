<?php 

class Common
{	
	var $conn;
	var $debug;
			
	function Common($debug)
	{
		$this->debug = $debug; 
		$rs = $this->connect("genega1"); // db name really here
		return $rs;
	}

// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */
	
	function connect($db)// connect to MySQL
	{
		

		$conn = @mysql_connect('studentdb-maria.gl.umbc.edu', 'genega1','k#$TVVa9N8Zh)%g6');


                if (!$conn) {
                    die("Connection failed->  $conn" . mysql_error() );
                } 
                
		$rs = @mysql_select_db($db, $conn) or die("<br> Could not connect to $db database <br>");





		$this->conn = $conn; 
	}


// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */
	
	function executeQuery($sql, $filename) // execute query
	{
		if($this->debug == true) { echo("$sql <br>\n"); }
		$rs = mysql_query($sql, $this->conn) or die("Could not execute query '$sql' in $filename"); 
		return $rs;
	}


	//returns the four letter abbreviation of the given major

	function compressMajor($major){

		$compressedMajor = $major;
	
		$compressedMajor = str_replace("Engineering Undecided","ENGR", $compressedMajor);
		$compressedMajor = str_replace("Computer Engineering","CMPE",$compressedMajor);
		$compressedMajor = str_replace("Computer Science","CMSC", $compressedMajor);
		$compressedMajor = str_replace("Chemical Engineering","CENG", $compressedMajor);
		$compressedMajor = str_replace("Mechanical Engineering","MENG", $compressedMajor);

		return $compressedMajor;
	}

	//returns the full major name from the given 4 letter abbreviation

	function uncompressMajor($major){

		$uncompressedMajor= $major;
	
		$uncompressedMajor= str_replace("ENGR","Engineering Undecided", $uncompressedMajor);
		$uncompressedMajor= str_replace("CMPE","Computer Engineering",$uncompressedMajor);
		$uncompressedMajor= str_replace("CMSC","Computer Science", $uncompressedMajor);
		$uncompressedMajor= str_replace("CENG","Chemical Engineering", $uncompressedMajor);
		$uncompressedMajor= str_replace("MENG","Mechanical Engineering", $uncompressedMajor);

		return $uncompressedMajor;
	}


	//removes redundant queries for getting appointments from student ID's
	function getRowFromAppointmentsForEnrolledID($studid, $scriptName)
	{
			$sql = "select * from Proj2Appointments where `EnrolledID` like '%$studid%'";
			$rs = $this->executeQuery($sql, $scriptName);
			$row = mysql_fetch_row($rs);
			return $row;
	}

	//removes redundant queries updating Students when they cancel
	function updateStudentsWhenStudentCancels($studid, $scriptName)
	{
		$sql = "update `Proj2Students` set `Status` = 'N' where `StudentID` = '$studid'";
		$rs = $this->executeQuery($sql, $scriptName);
	}
	
	//removes redundant queries for getting all advisors
	function getAllAdvisors($scriptName)
	{
		$sql = "select * from Proj2Advisors";
		$rs = $this->executeQuery($sql, $scriptName);
		return $rs;
	}

	//removes redundant queries for getting advisor names from advisor ID's
	function getAdvisorName($ID, $scriptName)
	{
		$sql = "select * from Proj2Advisors where `id` = '$ID'";
		$rs = $this->executeQuery($sql, $scriptName);
		$row = mysql_fetch_row($rs);

		$advisorName = $row[1] ." ". $row[2];

		return $advisorName;
	}

} // ends class, NEEDED!!

?>