<!DOCTYPE html>

<html>
<head>
	<meta charset="UTF-8">
    <title>Show Actor</title>
	<meta name="description" content="Show Actor">
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
	<h2>Actor Information:<br></h2><hr>
	<?php
		// Establish database connection
		$db_connection = databaseConnect();

		// Retrieve actor ID
		$id = mysql_real_escape_string($_GET["id"]);

		// Construct query
		$query_str = "SELECT last, first, sex, dob, dod FROM Actor WHERE id = $id";
		$name = $sex = $dob = $dod = "";
		$result = mysql_query($query_str, $db_connection);
		if (mysql_num_rows($result) > 0){
			$row = mysql_fetch_row($result);
			$name = htmlspecialchars($row[0]) . ", " . htmlspecialchars($row[1]);
			$sex = htmlspecialchars($row[2]);
			$dob = htmlspecialchars($row[3]);
			$dod = (is_null($row[4]))? "Still alive":htmlspecialchars($row[4]);
			mysql_free_result($result);
		}
	?>
	<table cellspacing="10">
		<tr>
			<td>Name:</td>
			<td><?php echo $name ?></td>
		</tr>
		<tr>
			<td>Gender:</td>
			<td><?php echo $sex ?></td>
		</tr>
		<tr>
			<td>Date of Birth:</td>
			<td><?php echo $dob ?></td>
		</tr>
		<tr>
			<td>Date of Death:</td>
			<td><?php echo $dod ?></td>
		</tr>
	</table>
	<br>
	<h2>Appeared in:</h2><hr>
	<?php
		// Construct query
		$query_str = "SELECT m.id, m.title, ma.role FROM MovieActor ma, Movie m WHERE m.id = ma.mid AND ma.aid = $id";
		// echo "" . $query_str;
		$result = mysql_query($query_str, $db_connection);
		if (mysql_num_rows($result) > 0){
			echo "<table cellspacing=\"10\"><tr><td style=\"font-weight:bold\">Movie</td><td style=\"font-weight:bold\">Role</td></tr>";
			while($row = mysql_fetch_row($result)){
				echo "<tr>";
				echo "<td>" . $row[2] . "</td>";
				echo "<td><a href=\"showMovieInfo.php?mid=$row[0]\", target=\"main_page\">$row[1]</a></tr>";
				echo "</td>";
			}
			echo "</table>";
			mysql_free_result($result);
		}
		else{
			echo "No results found";
		}

		// Close database connection
		databaseClose($db_connection);
	?>
</body>
</html>




















