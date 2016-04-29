<!DOCTYPE html>

<html>
<head>
	<meta charset="UTF-8">
    <title>Add Movie Information</title>
	<meta name="description" content="Add Movie Information">
	<meta name="author" content="Wesley Minner and Lui Liu">
	<style>.error {color: Red;}</style>
</head>	

<body>
	<h2>Add New Movie:<br></h2>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "GET">
		<table cellspacing="10">
			<tr>
				<td>Title<span style="color:red">*</span>:</td>
				<td>
					<input type="text" name="title" size="20" maxlength="20" required>
				</td>
			</tr>
			<tr>
				<td>Company<span style="color:red">*</span>:</td>
				<td>
					<input type="text" name="company" size="20" maxlength="20" required>
				</td>
			</tr>
			<tr>
				<td>Year<span style="color:red">*</span>:</td>
				<td>
					<input type="text" name="year" size="20" maxlength="20" required>
				</td>
			</tr>
			<tr>
				<td>MPAA Rating<span style="color:red">*</span>:</td>
				<td>
					<select>
						<option value="g">G</option>
						<option value="pg">PG</option>
						<option value="pg13">PG-13</option>
						<option value="r">R</option>
						<option value="nc17">NC-17</option>
					</select>
				</td>
			</tr>
		</table>
		<input type="submit" name="submit" value="Add"/>
	</form>
	<hr>

	<?php
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

			// Lookup MaxMovieID
			$query_str = "SELECT id FROM MaxMovieID";
			$result = mysql_query($query_str, $db_connection);
			$id = 0;
			if (mysql_num_rows($result) > 0){
				$row = mysql_fetch_row($result);
				$id = $row[0]+1;
				mysql_free_result($result);
			}
			// echo "MaxMovieID = $id";

			// Parse input data variables
			$title = "\"" . mysql_real_escape_string($_GET["title"]) . "\"";
			$company = "\"" . mysql_real_escape_string($_GET["lastName"]) . "\"";
			$year = "\"" . mysql_real_escape_string($_GET["gender"]) . "\"";
			$rating = "\"" . mysql_real_escape_string($_GET["dob"]) . "\"";
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
				$insert_str = "INSERT INTO Actor VALUES($id, $firstname, $lastname, $gender, $dob, $dod)";
			}else{
				$insert_str = "INSERT INTO Director VALUES($id, $firstname, $lastname, $dob, $dod)";
			}
			echo $insert_str;
			
			// Execute the INSERT statement
			if(!mysql_query($insert_str, $db_connection)){
				echo "ERROR: " . mysql_error($db_connection);
				exit(1);
			}

			// Increment MaxMovieID
			$update_id_str = "UPDATE MaxMovieID SET id = id + 1;";
			if(!mysql_query($update_id_str, $db_connection)){
				echo "ERROR: " . mysql_error($db_connection);
				exit(1);
			}

			$affected = mysql_affected_rows($db_connection);
			echo "SUCCESS: Total affected rows: $affected<br/>";

			// Close database connection 
			mysql_close($db_connection);
		}
		
	?>
</body>
</html>




















