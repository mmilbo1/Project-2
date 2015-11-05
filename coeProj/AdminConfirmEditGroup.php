<?php
session_start();
$debug = false;

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Edit Group Appointment</title>
    <script type="text/javascript">
    function saveValue(target){
	var stepVal = document.getElementById(target).value;
	alert("Value: " + stepVal);
    }
    </script>
	<link rel='stylesheet' type='text/css' href='./css/standard.css'/>
  </head>
  <body>
    <div id="login">
      <div id="form">
        <div class="top">
		<div class="field">
        <?php
          $delete = $_SESSION["Delete"];
          $group = $_SESSION["GroupApp"];
          parse_str($group);
 
          include('../CommonMethods.php');
          $COMMON = new Common($debug);

		

          if($delete == true){
            echo("<h1>Removed Appointment</h1><br>");


	

            $sql = "SELECT `EnrolledID` FROM `Proj2Appointments` WHERE `Time` = '$row[0]'
              AND `AdvisorID` = '0' 
              AND `Major` = '$row[1]' 
              AND `EnrolledNum` = '$row[2]'
              AND `Max` = '$row[3]'";
            $rs = $COMMON->executeQuery($sql, "Advising Appointments");

            $stds = mysql_fetch_row($rs);
	echo($stds[0]);
	    $stds = trim($stds[0]); // had some side white spaces sometimes
	    $stds = split(" ", $stds);

            if($debug) { var_dump("\n<BR>EMAILS ARE: $stds \n<BR>"); }
		// foreach($stds as $element) { echo("->".$element."\n"); }

            if($stds)
	    {


              foreach($stds as $element){
                $element = trim($element);
		$sql = "UPDATE `Proj2Students` SET `Status`='C' WHERE `StudentID` = '$element'";
                $rs = $COMMON->executeQuery($sql, "Advising Appointments");
                $sql = "SELECT `Email` FROM `Proj2Students` WHERE `StudentID` = '$element'";
                $rs = $COMMON->executeQuery($sql, "Advising Appointments");
                $ros = mysql_fetch_row($rs);
                $eml = $ros[0];
                $message = "The following group appointment has been deleted by the adminstration of your advisor: " . "\r\n" .
                "Time: $row[0]" . "\r\n" . 
                "To schedule for a new appointment, please log back into the UMBC COEIT Engineering and Computer Science Advising webpage." . "\r\n" .
		"http://coeadvising.umbc.edu  -> COEIT Advising Scheduling \r\n Reminder, this is only accessible on campus."; 

                mail($eml, "Your COE Advising Appointment Has Been Deleted", $message);
              }
            }

            $sql = "DELETE FROM `Proj2Appointments` WHERE `Time` = '$row[0]' 
              AND `AdvisorID` = '0' 
              AND `Major` = '$row[1]' 
              AND `EnrolledNum` = '$row[2]'
              AND `Max` = '$row[3]'";
            $rs = $COMMON->executeQuery($sql, "Advising Appointments");

            echo("Time: ". date('l, F d, Y g:i A', strtotime($row[0])). "<br>");
            echo("Majors included: ");

		$majors = $COMMON->uncompressMajor($row[1]);

            if($row[1]){ echo("$majors<br>"); }
            else{ echo("Available to all majors<br>"); }

            echo("Number of students enrolled: $row[2]<br>");
            echo("Student limit: $row[3]");
            echo("<br><br>");
            echo("<form method=\"link\" action=\"AdminUI.php\">");
            echo("<input type=\"submit\" name=\"next\" class=\"button large go\" value=\"Return to Home\">");
            echo("</form>");
            echo("</div>");
            echo("<div class=\"bottom\">");
            if($stds[0]){
              echo "<p style='color:red'>Students have been notified of the cancellation.</p>";
            }
          }
          else{
            echo("<h1>Changed Appointment</h1><br>");
			echo("<h2>Previous Appointment:</h2>");
            echo("Time: ". date('l, F d, Y g:i A', strtotime($row[0])). "<br>");
            echo("Majors included: ");
            if($row[1]){
              echo("$majors<br>"); 
            }
            else{
              echo("Available to all majors<br>"); 
            }
            echo("Number of students enrolled: $row[2]<br>");
            echo("Student limit: $row[3]");
            echo("<h2>Updated Appointment:</h2>");
            $limit = $_POST["stepper"];
            echo("<b>Time: ". date('l, F d, Y g:i A', strtotime($row[0])). "</b><br>");
            echo("<b>Majors included: ");
            if($row[1]){
              echo("$majors</b><br>"); 
            }
            else{
              echo("Available to all majors</b><br>"); 
            }
            echo("<b>Number of students enrolled: $row[2] </b><br>");
            echo("<b>Student limit: $limit</b>");

            $sql = "UPDATE `Proj2Appointments` SET `Max`='$limit' WHERE `Time` = '$row[0]' 
                    AND `AdvisorID` = '$0' AND `Major` = '$row[1]' 
                    AND `EnrolledNum` = '$row[2]' AND `Max` = '$row[3]'";
            $rs = $COMMON->executeQuery($sql, "Advising Appointments"); 

            echo("<br><br>");
            echo("<form method=\"link\" action=\"AdminUI.php\">");
            echo("<input type=\"submit\" name=\"next\" class=\"button large go\" value=\"Return to Home\">");
            echo("</form>");
          }
        ?>
	</div>
	</div>
	</div>
	</form>
  </body>
  
</html>
