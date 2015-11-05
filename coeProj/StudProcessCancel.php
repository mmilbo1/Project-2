<?php
session_start();
$debug = false;
include('../CommonMethods.php');
$COMMON = new Common($debug);

if($_POST["cancel"] == 'Cancel'){
	$firstn = $_SESSION["firstN"];
	$lastn = $_SESSION["lastN"];
	$studid = $_SESSION["studID"];
	$major = $_SESSION["major"];
	$email = $_SESSION["email"];
	
	//remove stud from EnrolledID
	
	//replaces sql query with function
	$row = $COMMON->getRowFromAppointmentsForEnrolledID($studid, $_SERVER["SCRIPT_NAME"]);



	$oldAdvisorID = $row[2];
	$oldAppTime = $row[1];
	$newIDs = str_replace($studid, "", $row[4]);
	
	$sql = "update `Proj2Appointments` set `EnrolledNum` = EnrolledNum-1, `EnrolledID` = '$newIDs' where `AdvisorID` = '$oldAdvisorID' and `Time` = '$oldAppTime' and `EnrolledNum` > 0";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	


	//update stud status to noApp



	//replaces sql query with function
	$COMMON->updateStudentsWhenStudentCancels($studid, $_SERVER["SCRIPT_NAME"]);
	


	
	$_SESSION["status"] = "cancel";
}
else{
	$_SESSION["status"] = "keep";
}
header('Location: 12StudExit.php');
?>