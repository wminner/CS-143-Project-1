<!DOCTYPE html>

<html>
<head>
	<meta charset="UTF-8">
    <title>Add Actor/Director</title>
	<meta name="description" content="Add Actor/Director">
	<meta name="author" content="Wesley Minner and Lui Liu">
	<style>.error {color: Red;}</style>
</head>	

<body>
	<h2>Add New Actor/Director:<br></h2>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "GET">
		<table cellspacing="10">
			<tr>
				<td>Identity<span style="color:red">*</span>:</td>
				<td>
					<input type="radio" name="identity" value="actor" checked="true">Actor
					<input type="radio" name="identity" value="director">Director
				</td>
			</tr>
			<tr>
				<td>First Name<span style="color:red">*</span>:</td>
				<td>
					<input type="text" name="firstName" size="20" maxlength="20" required>
				</td>
			</tr>
			<tr>
				<td>Last Name<span style="color:red">*</span>:</td>
				<td>
					<input type="text" name="lastName" size="20" maxlength="20" required>
				</td>
			</tr>
			<tr>
				<td>Gender<span style="color:red">*</span>:</td>
				<td>
					<input type="radio" name="gender" value="Male" checked="true">Male
					<input type="radio" name="gender" value="Female">Female
					<input type="radio" name="gender" value="Unspecified">Unspecified
				</td>
			</tr>
			<tr>
				<td>Date of Birth<span style="color:red">*</span></td>
				<td>
					<input type="text" name="dobYear" size="5" maxlength="4" required> -
					<input type="text" name="dobMonth" size="3" maxlength="2" required> -
					<input type="text" name="dobDay" size="3" maxlength="2" required>
					<span><i>(YYYY-MM-DD)</i></span>
				</td>
			</tr>
			<tr>
				<td>Date of Death</td>
				<td>
					<input type="text" name="dodYear" size="5" maxlength="4"> -
					<input type="text" name="dodMonth" size="3" maxlength="2"> -
					<input type="text" name="dodDay" size="3" maxlength="2">
					<span><i>(YYYY-MM-DD)</i></span>
				</td>
			</tr>
		</table>
		<input type="submit" name="submit" value="Add"/>
	</form>
	<hr>

	<?php
		$valid_genders = array("Male", "Female", "Unspecified");
		$valid_identity = array("actor", "director");

		if(isset($_GET['submit'])){
			$desired_db = "CS143";
			//$desired_db = "TEST";

			// Connect to mysql and check for errors
			$db_connection = mysql_connect("localhost", "cs143", "");
			if (!$db_connection) {
			    $errormsg = mysql_error($db_connection);
			    echo "ERROR: Error connecting to MySQL: " . $errormsg . "<br />";
			    exit(1);
			}

			// Switch to desired database
			$db_selected = mysql_select_db($desired_db, $db_connection);
			if (!$db_selected) {
			    $errormsg = mysql_error();
			    echo "ERROR: Failed to select database " . $desired_db . ": " . $errormsg . "<br />";
			    exit(1);
			}

			// Lookup MaxPersonID
			$query_str = "SELECT id FROM MaxPersonID";
			$result = mysql_query($query_str, $db_connection);
			$id = 0;
			if (mysql_num_rows($result) > 0){
				$row = mysql_fetch_row($result);
				$id = $row[0]+1;
				mysql_free_result($result);
			}

			// Parse input data variables
			$firstname = "\"" . mysql_real_escape_string($_GET["firstName"]) . "\"";
			$lastname = "\"" . mysql_real_escape_string($_GET["lastName"]) . "\"";

			// Check for valid gender
			if ($_GET["gender"] == "Unspecified" || !in_array($_GET["gender"], $valid_genders)){
				$gender = "NULL";
			}else{
				$gender = "\"" . mysql_real_escape_string($_GET["gender"]) . "\"";
			}

			// Check for valid dob date
			// $dob = "\"" . mysql_real_escape_string($_GET["dob"]) . "\"";
			$dob = "";
			if(checkdate($_GET["dobMonth"],$_GET["dobDay"], $_GET["dobYear"])){
				$dobDay = str_pad($_GET["dobDay"], 2, "0", STR_PAD_LEFT);
				$dobMonth = str_pad($_GET["dobMonth"], 2, "0", STR_PAD_LEFT);
				$dob = "\"" . $_GET["dobYear"]."-".$dobMonth."-".$dobDay . "\""	;

				echo "dob = " . $dob;
			}else{
				echo "ERROR: Please enter date of birth in the YYYY-MM-DD format";
				exit(1);
			}

			// Check for valid dob date
			// $dob = "\"" . mysql_real_escape_string($_GET["dob"]) . "\"";
			$dod = "";
			if(empty($_GET["dodYear"])&&empty($_GET["dodMonth"])&&empty($_GET["dodDay"])){
				$dod = "NULL";
			}else{
				if(checkdate($_GET["dodMonth"],$_GET["dodDay"], $_GET["dodYear"])){
					$dodDay = str_pad($_GET["dodDay"], 2, "0", STR_PAD_LEFT);
					$dodMonth = str_pad($_GET["dodMonth"], 2, "0", STR_PAD_LEFT);
					$dod = "\"" . $_GET["dodYear"]."-".$dodMonth."-".$dodDay. "\"" ;
					echo "dod = " . $dod;

					if($dod < $dob){
						echo "ERROR: date of birth should precede date of death";
						exit(1);
					}
				}else{
					echo "ERROR: Please enter date of death in the YYYY-MM-DD format";
					exit(1);
				}
			}

			// Construct the INSERT statement
			// Check if identity is either actor or director
			if (in_array($_GET["identity"], $valid_identity)) {
				$insert_str = "";
				if($_GET["identity"]=="actor"){
					$insert_str = "INSERT INTO Actor VALUES($id, $lastname, $firstname, $gender, $dob, $dod)";
				}else{
					$insert_str = "INSERT INTO Director VALUES($id, $lastname, $firstname, $dob, $dod)";
				}
				
				// Execute the INSERT statement
				if(!mysql_query($insert_str, $db_connection)){
					echo "ERROR: " . mysql_error($db_connection);
					exit(1);
				}

				// Increment MaxPersonID
				$update_id_str = "UPDATE MaxPersonID SET id = id + 1;";
				if(!mysql_query($update_id_str, $db_connection)){
					echo "ERROR: " . mysql_error($db_connection);
					exit(1);
				}

				// Check for succcess, then provide link to new page (if actor)
				$affected = mysql_affected_rows($db_connection);
				if ($affected == 1)
					if ($_GET['identity'] == "actor")
						echo "SUCCESS: <a href=\"showActorInfo.php?aid=$id\">view actor's page</a><br />";
					else
						echo "SUCCESS!<br />";
			}

			// Close database connection 
			mysql_close($db_connection);
		}
	?>
</body>
</html>




















