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
				<td>Company:</td>
				<td>
					<input type="text" name="company" size="20" maxlength="20">
				</td>
			</tr>
			<tr>
				<td>Year:</td>
				<td>
					<input type="text" name="year" size="20" maxlength="20">
					(YYYY)
				</td>
			</tr>
			<tr>
				<td>MPAA Rating:</td>
				<td>
					<select name="rating">>
						<option value="NULL"></option>
						<option value="G">G</option>
						<option value="PG">PG</option>
						<option value="PG-13">PG-13</option>
						<option value="R">R</option>
						<option value="NC-17">NC-17</option>
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

			$company = "";
			if (!empty($_GET["company"]))
				$company = "\"" . mysql_real_escape_string($_GET["company"]) . "\"";
			else
				$company = "NULL";

			$year = "";
			if (!empty($_GET["year"]))
				$year = intval(mysql_real_escape_string($_GET["year"]));
			else
				$year = "NULL";

			// TODO verify valid mpaa rating
			$rating = "";
			if (!empty($_GET["rating"]))
				$rating = "\"" . mysql_real_escape_string($_GET["rating"]) . "\"";
			else
				$rating = "NULL";
			echo "Parsing completed: title = $title, company = $company, year = $year, rating = $rating" . "<br /><br />";

			// Construct the INSERT statement
			$insert_str = "INSERT INTO Movie VALUES($id, $title, $year, $rating, $company)";
			// echo "Query: " . $insert_str . "<br /><br />";
			
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




















