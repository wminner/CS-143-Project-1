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
					<input type="text" name="dob" size="20" maxlength="20" required>
					(YYYY-MM-DD)
				</td>
			</tr>
			<tr>
				<td>Date of Death</td>
				<td>
					<input type="text" name="dod" size="20" maxlength="20">
					(YYYY-MM-DD)
				</td>
			</tr>
		</table>
		<input type="submit" name="submit" value="Add"/>
	</form>
	<hr>

	<?php
		$valid_genders = array("Male", "Female", "Unspecified");

		if(isset($_GET['submit'])){
			//$desired_db = "CS143";
			$desired_db = "TEST";

			// Connect to mysql and check for errors
			$db_connection = mysql_connect("localhost", "cs143", "");
			if (!$db_connection) {
			    $errormsg = mysql_error($db_connection);
			    echo "Error connecting to MySQL: " . $errormsg . "<br />";
			    exit(1);
			}

			// Switch to desired database
			$db_selected = mysql_select_db($desired_db, $db_connection);
			if (!$db_selected) {
			    $errormsg = mysql_error();
			    echo "Failed to select database " . $desired_db . ": " . $errormsg . "<br />";
			    exit(1);
			}
			// echo "Database connection established";

			// Lookup MaxPersonID
			$query_str = "SELECT id FROM MaxPersonID";
			$result = mysql_query($query_str, $db_connection);
			$id = 0;
			if (mysql_num_rows($result) > 0){
				$row = mysql_fetch_row($result);
				$id = $row[0]+1;
				mysql_free_result($result);
			}
			// echo "MaxPersonID = $id";

			// Parse input data variables
			$firstname = "\"" . mysql_real_escape_string($_GET["firstName"]) . "\"";
			$lastname = "\"" . mysql_real_escape_string($_GET["lastName"]) . "\"";
			// TODO validate gender
			if ($_GET["gender"] == "Unspecified" || !in_array($_GET["gender"], $valid_genders))
				$gender = "NULL";
			else
				$gender = "\"" . mysql_real_escape_string($_GET["gender"]) . "\"";
			$dob = "\"" . mysql_real_escape_string($_GET["dob"]) . "\"";
			$dod = "";
			if(!empty($_GET["dod"])){
				$dod = "\"" . mysql_real_escape_string($_GET["dod"]) . "\"";
			}else{
				$dod = "NULL";
			}
			// echo "Parsing completed: firstName = $firstname, lastname = $lastname, gender = $gender, dob = $dob, dod = $dod";

			// Construct the INSERT statement
			$insert_str = "";
			if($_GET["identity"]=="actor"){
				$insert_str = "INSERT INTO Actor VALUES($id, $lastname, $firstname, $gender, $dob, $dod)";
			}else{
				$insert_str = "INSERT INTO Director VALUES($id, $lastname, $firstname, $dob, $dod)";
			}
			echo "Query: " . $insert_str . "<br /><br />";
			
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

			$affected = mysql_affected_rows($db_connection);
			echo "SUCCESS: Total affected rows: $affected<br /><br />";

			// Close database connection 
			mysql_close($db_connection);
		}
		
	?>
</body>
</html>




















