<!DOCTYPE html>

<html>
<head>
	<meta charset="UTF-8">
    <title>Add Movie Director Relation</title>
	<meta name="description" content="Add Movie Director Relation">
	<meta name="author" content="Wesley Minner and Lui Liu">
	<style>.error {color: Red;}</style>
	<?php
		function databaseConnect(){
			$desired_db = "CS143";
			//$desired_db = "TEST";

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
			return $db_connection;
		}
		function databaseClose($db_connection){
			mysql_close($db_connection);
		}
	?>
</head>	

<body>
	<h2>Add New Movie Director Relation:<br></h2>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "GET">
		<table cellspacing="10">
			<tr>
				<td>Movie<span style="color:red">*</span>:</td>
				<td>
					<select name="movie" required>
						<?php
							// Establish database connection
							$db_connection = databaseConnect();

							// Populate Movie Dropdown menu
							$query_str = "SELECT id, title, year FROM Movie";
							$result = mysql_query($query_str, $db_connection);
							if (mysql_num_rows($result) > 0){
								while ($row = mysql_fetch_row($result)) {
						            echo "<option value=\"$row[0]\">";
						            echo "$row[1] ($row[2])";
						            echo "</option>";
						        }
								mysql_free_result($result);
							}

							// Close database connection
							databaseClose($db_connection);
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Director<span style="color:red">*</span>:</td>	
				<td>
					<select name="director" required>
						<?php
							// Establish database connection
							$db_connection = databaseConnect();

							// Populate Director Dropdown menu
							$query_str = "SELECT id, last, first, dob FROM Director";
							$result = mysql_query($query_str, $db_connection);
							if (mysql_num_rows($result) > 0){
								while ($row = mysql_fetch_row($result)) {
						            echo "<option value=\"$row[0]\">";
						            echo "$row[1], $row[2] ($row[3])";
						            echo "</option>";
						        }
								mysql_free_result($result);
							}

							// Close database connection
							databaseClose($db_connection);
						?>
					</select>
				</td>
			</tr>
		</table>
		<input type="submit" name="submit" value="Add"/>
	</form>
	<hr>

	<?php
		if(isset($_GET['submit'])){
			// Establish database connection
			$db_connection = databaseConnect();

			// Construct the INSERT statement
			$movie = $_GET['movie'];
			$director = $_GET['director'];
			$insert_str = "INSERT INTO MovieDirector VALUES(\"$movie\", \"$director\")";
			
			// Execute the INSERT statement
			if(!mysql_query($insert_str, $db_connection)){
				echo "ERROR: " . mysql_error($db_connection);
				exit(1);
			}

			// Print success and a link to the updated movie's page
			$affected = mysql_affected_rows($db_connection);
			if ($affected == 1)
				echo "SUCCESS: <a href=\"showMovieInfo.php?mid=$movie\">view movie's page</a><br />";

			// Close database connection 
			databaseClose($db_connection);
		}
		
	?>
</body>
</html>




















