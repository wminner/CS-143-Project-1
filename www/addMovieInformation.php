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
				<td><input type="text" name="title" size="20" maxlength="20" required></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>IMDB Score:</td>
				<td><input type="number" name="imdb" min="0" max="10" step="0.1" style="max-width:50px">&nbsp&nbsp(0.0-10.0)</td>
			</tr>
			<tr>
				<td>Company:</td>
				<td><input type="text" name="company" size="20" maxlength="20"></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>Rotten Tomatoes Score:</td>
				<td><input type="number" name="rot" min="0" max="10" step="0.1" style="max-width:50px">&nbsp&nbsp(0.0-10.0)</td>
			</tr>
			<tr>
				<td>Year:</td>
				<td><input type="text" name="year" size="20" maxlength="4"></td>
			</tr>
			<tr>
				<td>Genre: <br /> (separate by spaces)</td>
				<td><input type="text" name="genre" list="genre" size="20"/>
					<datalist id="genre">
						<option>Action</option>
						<option>Adult</option>
						<option>Adventure</option>
						<option>Animation</option>
						<option>Comedy</option>
						<option>Crime</option>
						<option>Documentary</option>
						<option>Drama</option>
						<option>Family</option>
						<option>Fantasy</option>
						<option>Horror</option>
						<option>Musical</option>
						<option>Mystery</option>
						<option>Romance</option>
						<option>Sci-Fi</option>
						<option>Short</option>
						<option>Thriller</option>
						<option>War</option>
						<option>Western</option>
					</datalist>
				</td>
			</tr>
			<tr>
				<td>MPAA Rating:</td>
				<td><select name="rating" style="min-width:100%">
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
		$valid_ratings = array("G", "PG", "PG-13", "R", "NC-17");
		$num_insert = 0;

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

			// Lookup MaxMovieID
			$query_str = "SELECT id FROM MaxMovieID";
			$result = mysql_query($query_str, $db_connection);
			$id = 0;
			if (mysql_num_rows($result) > 0){
				$row = mysql_fetch_row($result);
				$id = $row[0]+1;
				mysql_free_result($result);
			}

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

			$genre_str = "";
			if (!empty($_GET["genre"])) {
				$genre_str = "\"" . mysql_real_escape_string($_GET["genre"]) . "\"";
				$genre_str = str_replace("\"", "", $genre_str);
				$genre_array = explode(' ', $genre_str);
			}
			else
				$genre_str = "NULL";

			$rating = "";
			if (!empty($_GET["rating"]) && in_array($_GET["rating"], $valid_ratings))
				$rating = "\"" . mysql_real_escape_string($_GET["rating"]) . "\"";
			else
				$rating = "NULL";

			$imdb = "";
			if (!empty($_GET["imdb"]))
				$imdb = intval(10*mysql_real_escape_string($_GET["imdb"]));
			else
				$imdb = "NULL";

			$rot = "";
			if (!empty($_GET["rot"]))
				$rot = intval(10*mysql_real_escape_string($_GET["rot"]));
			else
				$rot = "NULL";

			// Construct INSERT statements
			// Construct movie insert
			$insert_movie_str = "INSERT INTO Movie VALUES($id, $title, $year, $rating, $company)";
			// Construct genre insert
			$insert_genre_array = array();
			if ($genre_str != "NULL") {
				foreach ($genre_array as $genre)
					array_push($insert_genre_array, "INSERT INTO MovieGenre VALUES($id, \"$genre\")");
			} else
				$insert_genre_array = "NULL";
			// Construct rating insert
			if ($imdb != "NULL" || $rot != "NULL")
				$insert_rating_str = "INSERT INTO MovieRating VALUES($id, $imdb, $rot)";
			else
				$insert_rating_str = "NULL";

			// Execute the INSERT statements
			// Insert into Movie
			$affected = 0;
			if(!mysql_query($insert_movie_str, $db_connection)){
				echo "ERROR: " . mysql_error($db_connection);
				exit(1);
			}
			$num_insert += 1;
			$affected += mysql_affected_rows($db_connection);
			
			// Insert genres into MovieGenre
			if ($insert_genre_array != "NULL") {
				foreach ($insert_genre_array as $insert_genre_str) {
					if (!mysql_query($insert_genre_str, $db_connection)) {
						echo "ERROR: " . mysql_error($db_connection);
						exit(1);
					}
					$num_insert += 1;
					$affected += mysql_affected_rows($db_connection);
				}
			}

			// Insert rating into MovieRating
			if ($insert_rating_str != "NULL" && !mysql_query($insert_rating_str, $db_connection)) {
				echo "ERROR: " . mysql_error($db_connection);
				exit(1);
			}
			$num_insert += 1;
			$affected += mysql_affected_rows($db_connection);

			// Increment MaxMovieID
			$update_id_str = "UPDATE MaxMovieID SET id = id + 1;";
			if(!mysql_query($update_id_str, $db_connection)){
				echo "ERROR: " . mysql_error($db_connection);
				exit(1);
			}
			$num_insert += 1;
			$affected += mysql_affected_rows($db_connection);

			// Print success and a link to the new movie's page
			if ($affected == $num_insert)
				echo "SUCCESS: <a href=\"showMovieInfo.php?mid=$id\">view movie's page</a><br />";

			// Close database connection 
			mysql_close($db_connection);
		}
	?>
</body>
</html>




















