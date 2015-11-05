<?php
session_start();
$debug = false;
include('../CommonMethods.php');
$COMMON = new Common($debug);

$studID = $_SESSION["studID"];
?>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>View Appointment</title>
	<link rel='stylesheet' type='text/css' href='./css/standard.css'/>
  </head>
  <body>
    <div id="login">
      <div id="form">
        <div class="top">
		<h1>View Appointment</h1>
	    <div class="field">
	    <?php
			$sql = "select * from Proj2Appointments where `EnrolledID` like '%$studID%'";
			$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

			// if for some reason there really isn't a match, (something got messed up, tell them there really isn't one there)

			$num_rows = mysql_num_rows($rs);


			if($num_rows > 0)
			{
				$row = mysql_fetch_row($rs); // get legit data
				$advisorID = $row[2];
				$datephp = strtotime($row[1]);
				
				if($advisorID != 0){

				
					//gets the advisor

					$sql2 = "select * from Proj2Advisors where `id` = '$advisorID'";
					$rs2 = $COMMON->executeQuery($sql2, $_SERVER["SCRIPT_NAME"]);
					$row2 = mysql_fetch_row($rs2);
					
					$advisorName = $COMMON->getAdvisorName($advisorID, $_SERVER["SCRIPT_NAME"]);

					$location = $row2[5];
				}

				//displays the appointment
				else{$advisorName = "Group";}
			
				echo "<label for='info'>";
				echo "Advisor: ", $advisorName, "<br>";

				//displays advisor's location
				echo "Location: ", $location, "<br>";

				echo "Appointment: ", date('l, F d, Y g:i A', $datephp), "</label>";
				
			}
			else // something is up, and there DB table needs to be fixed
			{
				echo("No appointment was detected. It may have been cancelled. Please make another appointment.");

				//replaces sql query with function
				$COMMON->updateStudentsWhenStudentCancels($studID, $_SERVER["SCRIPT_NAME"]);

				
			}
	

		?>
        </div>
	    <div class="finishButton">
			<button onclick="location.href = '02StudHome.php'" class="button large go" >Return to Home</button>
	    </div>
		</div>
		</form>
  </body>
</html>