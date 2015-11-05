<?php
session_start();
//ini_set('display_errors','1');
//ini_set('display_startup_errors','1');
//error_reporting (E_ALL);

$debug = false;
include('../CommonMethods.php');
$COMMON = new Common($debug);
?>



<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Search for Appointment</title>
	<link rel='stylesheet' type='text/css' href='./css/standard.css'/>
  </head>
  <body>
    <div id="login">
      <div id="form">
        <div class="top">
		<h1>Search Results</h1>
		<h3>Showing open appointments only</h3>
	    <div class="field">
			<p>Showing results for: </p>
			<?php
				$date = $_POST["date"];
				$times = $_POST["time"];
				$advisor = $_POST["advisor"];
				$results = array();


				
				if($date == ''){ echo "Date: All"; }
				else{ 
					echo "Date: ",$date;
					$date = date('Y-m-d', strtotime($date));
				}
				echo "<br>";
				if(empty($times)){ echo "Time: All"; }
				else{
					$i = 0;
					echo "Time: ";

					foreach($times as $t){
						echo ++$i, ") ", date('g:i A', strtotime($t)), " ";
					}
				}
				echo "<br>";
				if($advisor == ''){ echo "Advisor: All appointments"; }
				elseif($advisor == 'I'){ echo "Advisor: All individual appointments"; }
				elseif($advisor == '0'){ echo "Advisor: All group appointments"; }
				else{
					$sql = "select * from Proj2Advisors where `id` = '$advisor'";
					$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
					while($row = mysql_fetch_row($rs)){
						echo "Advisor: ", $row[1], " ", $row[2];
					}
				}
				?>
				<br><br><label>
				<?php

				//gets a string of the abbreviated majors for storage in the database
				$compressedMajor = $COMMON->compressMajor($_SESSION["major"]);

				//executes when there are no times
				
				if(empty($times)){

					//gets individual appointment
					if($advisor == 'I')
					{
						$sql = "select * from Proj2Appointments where `Time` like '%$date%' and `Time` > '".date('Y-m-d H:i:s')."' and `AdvisorID` != 0 and `EnrolledNum` = 0 and `Major` like '%$compressedMajor%' order by `Time` ASC Limit 30";
						$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

					}
					//gets group appointment
					else
					{
						$sql = "select * from Proj2Appointments where `Time` like '%$date%' and `Time` > '".date('Y-m-d H:i:s')."' and `AdvisorID` like '%$advisor%' and `EnrolledNum` = 0 and `Major` like '%$compressedMajor%' order by `Time` ASC Limit 30";
						$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
					}

					$row = mysql_fetch_row($rs);
					$rsA = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

					if($row)
					{
						
						while($row = mysql_fetch_row($rsA)){

							//gets a string of full majors from the abbreviated majors in the database
							$uncompressedMajor = $COMMON->uncompressMajor($row[3]);


							if($row[2] == 0){
								$advName = "Group";
							}
							else{ $advName = getAdvisorName($row); }
							


							$found = 	"<tr><td>". date('l, F d, Y g:i A', strtotime($row[1]))."</td>".
									"<td>". $advName."</td>". 
									"<td>". $uncompressedMajor. "</td></tr>".

							array_push($results, $found);
						}
					}
				}
				else{
					if($advisor == 'I'){
						foreach($times as $t){
							$sql = "select * from Proj2Appointments where `Time` like '%$date%' and `Time` > '".date('Y-m-d H:i:s')."' and `Time` like '%$t%' and `AdvisorID` != 0 and `EnrolledNum` = 0 and `Major` like '%$compressedMajor%' order by `Time` ASC Limit 30";
							$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
							$row = mysql_fetch_row($rs);
							$rsA = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
							if($row){
								while($row = mysql_fetch_row($rsA)){



									$uncompressedMajor = $COMMON->uncompressMajor($row[3]);


									if($row[2] == 0){
										$advName = "Group";
									}
									else{ $advName = getAdvisorName($row); }

							$found = 	"<tr><td>". date('l, F d, Y g:i A', strtotime($row[1]))."</td>".
									"<td>". $advName."</td>". 
									"<td>". $uncompressedMajor. "</td></tr>".
									array_push($results, $found);
								}
							}
						}
					}
					else{
						foreach($times as $t){
							$sql = "select * from Proj2Appointments where `Time` like '%$date%' and `Time` > '".date('Y-m-d H:i:s')."' and `Time` like '%$t%' and `AdvisorID` like '%$advisor%' and `EnrolledNum` = 0 and `Major` like '%$compressedMajor%' order by `Time` ASC Limit 30";
							$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
							$row = mysql_fetch_row($rs);
							if($row){
								while($row = mysql_fetch_row($rs)){


									$uncompressedMajor = $COMMON->uncompressMajor($row[3]);


									if($row[2] == 0){
										$advName = "Group";
									}
									else{ $advName = getAdvisorName($row); }

							$found = 	"<tr><td>". date('l, F d, Y g:i A', strtotime($row[1]))."</td>".
									"<td>". $advName."</td>". 
									"<td>". $uncompressedMajor. "</td></tr>".
									array_push($results, $found);
								}
							}
						}
					}
				}
				if(empty($results)){
					echo "No results found.<br><br>";
				}
				else{
					echo("<table border='1'><th colspan='3'>Appointments Available</th>\n");
					echo("<tr><td width='60px'>Time:</td><td>Advisor</td><td>Major</td></tr>\n");

					foreach($results as $r){ echo($r."\n"); }

					echo("</table>");
				}
			?>
			</label>
        </div>
		<form action="02StudHome.php" method="link">
	    <div class="nextButton">
			<input type="submit" name="done" class="button large go" value="Done">
	    </div>
		</form>
		</div>
		<div class="bottom">
		<p>If the Major category is followed by a blank, then it is open for all majors.</p>
		</div>
  </body>
</html>

<?php


// More code reduction by Lupoli - 9/1/15
// just getting the advisor's name
function getAdvisorName($row)
{
	global $debug; global $COMMON;
	$sql2 = "select * from Proj2Advisors where `id` = '$row[2]'";
	$rs2 = $COMMON->executeQuery($sql2, $_SERVER["SCRIPT_NAME"]);
	$row2 = mysql_fetch_row($rs2);
	return $row2[1] ." ". $row2[2];
}

?>