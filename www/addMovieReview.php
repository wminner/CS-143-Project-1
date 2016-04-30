<!DOCTYPE html>

<html>
<head>
	<meta charset="UTF-8">
    <title>Add Movie Review</title>
	<meta name="description" content="Add Movie Review">
	<meta name="author" content="Wesley Minner and Lui Liu">
	<style>.error {color: Red;}</style>
	<?php
		function databaseConnect(){
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
			return $db_connection;
		}
		function databaseClose($db_connection){
			mysql_close($db_connection);
		}
	?>
</head>	

<body>
	<h2>Add Movie Review:<br></h2>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "GET">
		<table cellspacing="10">
			<tr>
				<td>Movie<span style="color:red">*</span>:</td>
				<td>
					<select name="movie" required>
						<?php
							// Establish database connection
							$db_connection = databaseConnect();

							// Auto-select movie if an mid is submitted
							if (isset($_GET['mid'])) {
								$mid = $_GET['mid'];
								$mid = intval(mysql_real_escape_string($mid, $db_connection));
							}

							// Populate Movie Dropdown menu
							$query_str = "SELECT id, title, year FROM Movie";
							$result = mysql_query($query_str, $db_connection);
							if (mysql_num_rows($result) > 0){
								while ($row = mysql_fetch_row($result)) {
						            if ($row[0] == $mid)
						            	echo "<option selected value=\"$row[0]\">";
						            else
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
				<td>Name<span style="color:red">*</span>:</td>
				<td>
					<input type="text" name="name" size="20" maxlength="20" value="Anonymous" required/>
				</td>
			</tr>
			<tr>
				<td>Rating<span style="color:red">*</span>:</td>
				<td>
					<select name="rating" required>
						<option value="1">Very Poor (Rating 1)</option>
						<option value="2">Poor (Rating 2)</option>
						<option selected value="3">Fair (Rating 3)</option>
						<option value="4">Good (Rating 4)</option>
						<option value="5">Very Good (Rating 5)</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Comments<span style="color:red">*</span><br>(500 Characters):</td>
				<td>
					<textarea name="comment" rows="15" cols="63" maxlength="500" required></textarea>
				</td>
			</tr>
			<tr>
				<td></td>
				<td align="right"><input type="submit" name="submit" style="width:100px" value="Add"/></td>
			</tr>
		</table>
	</form>
	<hr>

	<?php
		if(isset($_GET['submit'])){
			// Establish database connection
			$db_connection = databaseConnect();

			// Parse the input
			$movie = mysql_real_escape_string($_GET['movie']);
			$name = mysql_real_escape_string($_GET['name']);
			$rating = mysql_real_escape_string($_GET['rating']);
			$comment = mysql_real_escape_string($_GET['comment']);
			if(strlen($comment) > 500){
				$comment = substr($comment, 0, 500); 
			}

			// Construct the INSERT statement
			$insert_str = "INSERT INTO Review VALUES(\"$name\", NOW(), \"$movie\", \"$rating\", \"$comment\")";
			
			// Execute the INSERT statement
			if(!mysql_query($insert_str, $db_connection)){
				echo "ERROR: " . mysql_error($db_connection);
				exit(1);
			}

			// Print success and a link to the updated movie's page
			$affected = mysql_affected_rows($db_connection);
			if ($affected == 1)
				echo "SUCCESS: <a href=\"showMovieInfo.php?mid=$movie\">view movie's page</a><br /><br />";

			// Close database connection 
			databaseClose($db_connection);
		}
		
	?>
</body>
</html>




















