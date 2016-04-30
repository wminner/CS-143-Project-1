<!DOCTYPE html>

<html>
<head>
	<meta charset="UTF-8">
    <title>Search Actor/Movie</title>
	<meta name="description" content="CS 143 Project 1">
	<meta name="author" content="Wesley Minner and Lui Liu">
	<style>.error {color: Red;}</style>
</head>

<body>

<h2>Search for Actors/Movies:</h2>
<form action="<?php $_PHP_SELF ?>" method="GET">
<table cellspacing="10">
    <tr>
        <td><input type="text" name="query" size="40" maxlength="40" required value="<?php echo htmlspecialchars($_GET['query']); ?>" /></td>
        <td><input type="submit" value="Search"/></td>
    </tr>
    <tr>
        <td>
        <input type="checkbox" name="actor" value="checked" checked />Actor
        <input type="checkbox" name="movie" value="checked" checked />Movie
        </td>
    </tr>
</table>
</form>

</body>

<?php
// *****************************************************************************
// Main
// *****************************************************************************

// Initialize variables
$query_str = $result = "";
$actor = $movie = True;
$actor_query = $movie_query = "";
#$desired_db = "CS143";
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

// Get checkbox values
if (!isset($_GET['actor']))
    $actor = False;
if (!isset($_GET['movie']))
    $movie = False;

// Create queries based on checkbox filters
if ($keyword = $_GET["query"]) {
    mysql_real_escape_string($keyword, $db_connection);
    if ($actor) 
        $actor_query = "SELECT * FROM Actor WHERE first LIKE '%$keyword%' OR last like '%$keyword%'";
    if ($movie)
        $movie_query = "SELECT * FROM Movie WHERE title LIKE '%$keyword%'";
    echo "<h2>Results:</h2>";
}

// Process queries and print results
if ($actor_query) {
    //echo "$actor_query<br />";
    $actor_result = mysql_query($actor_query, $db_connection);
    displayActorResult($actor_result);
}
if ($movie_query) {
    //echo "$movie_query<br />";
    $movie_result = mysql_query($movie_query, $db_connection);
    displayMovieResult($movie_result);
}

mysql_close($db_connection);

// *****************************************************************************
// Helper Functions
// *****************************************************************************

function displayActorResult($rs) {
    $num_rows = mysql_num_rows($rs);
    echo "Found $num_rows Actor(s).<br /><br />";
    if (mysql_num_rows($rs) > 0) {
        echo "<table border=0 cellspacing=2 cellpadding=2><tr>";
        while ($row = mysql_fetch_row($rs))
            echo "<tr><td><a href=\"showActorInfo.php?aid=$row[0]\">$row[2] $row[1] ($row[4])</a></td></tr>";
        echo "</table><br />";
    }
    mysql_free_result($rs);
}

function displayMovieResult($rs) {
    $num_rows = mysql_num_rows($rs);
    echo "Found $num_rows Movie(s).<br /><br />";
    if (mysql_num_rows($rs) > 0) {
        echo "<table border=0 cellspacing=2 cellpadding=2><tr>";
        while ($row = mysql_fetch_row($rs)) {
            echo "<tr><td><a href=\"showMovieInfo.php?mid=$row[0]\">$row[1] ($row[2])</a></td></tr>";
        }
        echo "</table><br />";
    }
    mysql_free_result($rs);
}
?>

</html>