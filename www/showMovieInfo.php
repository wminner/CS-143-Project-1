<!DOCTYPE html>

<html>
<head>
	<meta charset="UTF-8">
    <title>Movie Information</title>
	<meta name="description" content="CS 143 Project 1">
	<meta name="author" content="Wesley Minner and Lui Liu">
	<style>.error {color: Red;}</style>
</head>

<body>
<h2>Movie Information:</h2>

<?php
// Get information of movie requested
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

// Grab mid if we were redirected here
if ($mid = $_GET['mid'])
    mysql_real_escape_string($mid, $db_connection);

// Form queries and get results
$movie_query = "SELECT * FROM Movie WHERE id = $mid";
$direct_query = "SELECT first, last, dob FROM Director, MovieDirector WHERE mid = $mid AND id = did";
$genre_query = "SELECT * FROM MovieGenre WHERE mid = $mid";
$actor_query = "SELECT id, first, last, role FROM Actor, MovieActor WHERE mid = $mid AND id = aid";

$movie_rs = mysql_query($movie_query, $db_connection);
$direct_rs = mysql_query($direct_query, $db_connection);
$genre_rs = mysql_query($genre_query, $db_connection);
$actor_rs = mysql_query($actor_query, $db_connection);

// Display result
$movie_row = mysql_fetch_row($movie_rs);
if (!empty($movie_row)) {
    echo "<table border=0 cellspacing=2 cellpadding=2>";
    echo "<tr><td>Title: </td><td>$movie_row[1] ($movie_row[2])</td></tr>";
    echo "<tr><td>Producer: </td><td>$movie_row[4]</td></tr>";
    echo "<tr><td>MPAA Rating: </td><td>$movie_row[3]</td></tr>";
    
    // List all genres
    echo "<tr><td>Genre(s): </td>";
    $num_rows = mysql_num_rows($genre_rs);
    $count = 0;
    while ($genre_row = mysql_fetch_row($genre_rs)) {
        $count += 1;
        if ($count < $num_rows)
            echo "<td>$genre_row[1]</td></tr><tr><td></td>";
        else
            echo "<td>$genre_row[1]</td></tr><tr><tr><td></td><td></td></tr>";
    }

    // List all directors
    echo "<tr><td>Director(s): </td>";
    $num_rows = mysql_num_rows($direct_rs);
    $count = 0;
    while ($direct_row = mysql_fetch_row($direct_rs)) {
        $count += 1;
        if ($count < $num_rows)
            echo "<td>$direct_row[0] $direct_row[1] ($direct_row[2])</td></tr><tr><td></td>";
        else
            echo "<td>$direct_row[0] $direct_row[1] ($direct_row[2])</td></tr><tr><td></td><td></td></tr>";
    }

    // List all actors
    echo "<tr><td>Actor(s): </td>";
    $num_rows = mysql_num_rows($actor_rs);
    $count = 0;
    while ($actor_row = mysql_fetch_row($actor_rs)) {
        $count += 1;
        if ($count < $num_rows)
            echo "<td><a href=\"showActorInfo.php?aid=$actor_row[0]\">$actor_row[1] $actor_row[2]</a> as \"$actor_row[3]\"</td></tr><tr><td></td>";
        else
            echo "<td><a href=\"showActorInfo.php?aid=$actor_row[0]\">$actor_row[1] $actor_row[2]</a> as \"$actor_row[3]\"</td></tr><tr><td></td><td></td></tr>";
    }
    echo "</table><br />";

    // List all reviews
    echo "<h3>User Reviews:</h3><br />";
    echo "<table border=1 cellspacing=2 cellpadding=2>";
    // TODO
    echo "</table><br />";   
}

// Free all results
mysql_free_result($movie_rs);
mysql_free_result($movdir_rs);
mysql_free_result($genre_rs);
mysql_free_result($actor_rs);
mysql_free_result($direct_rs);

?>
</body>

</html>