<!DOCTYPE html>

<html>
<head>
	<meta charset="UTF-8">
    <title>CS 143 - Project 1 Database Query</title>
	<meta name="description" content="CS 143 Project 1, Professor ">
	<meta name="author" content="Wesley Minner and Lui Liu">
	<style>.error {color: Red;}</style>
</head>

<body>

<h2>Enter MySQL query below:</h2>
<p>
<form action="<?php $_PHP_SELF ?>" method="GET">
<textarea name="query" cols="80" rows="10"><?php echo htmlspecialchars($_GET['query']); ?></textarea><br />
<input type="submit" value="Submit"/>
</form>
</p>
<p>Table reference:</p>
    <ul><li>Movie</li>
        <li>Actor</li>
        <li>Sales</li>
        <li>Director</li>
        <li>MovieGenre</li>
        <li>MovieDirector</li>
        <li>MovieActor</li>
        <li>MovieRating</li>
        <li>Review</li>
        <li>MaxPersonID</li>
        <li>MaxMovieID</li>
    </ul>

</body>

<?php
// *****************************************************************************
// Main
// *****************************************************************************

// Initialize variables
$query_str = $result = "";
$desired_db = "TEST";

// Connect to mysql and check for errors
$db_connection = mysql_connect("localhost", "cs143", "");
if (!$db_connection) {
    $errormsg = mysql_error($db_connection);
    echo "Error connecting to MySQL: " . $errormsg . "<br />";
    exit(1);
}

// DEBUG
// $db_list = mysql_list_dbs($db_connection);
// while ($row = mysql_fetch_object($db_list)) {
//     echo $row->Database . "<br />"; 
// }

// Switch to desired database
$db_selected = mysql_select_db($desired_db, $db_connection);
if (!$db_selected) {
    $errormsg = mysql_error();
    echo "Failed to select database " . $desired_db . ": " . $errormsg . "<br />";
    exit(1);
}

// Fill in query string when user submits
if ( $_GET["query"] ) {
    $query_str = processQuery($_GET["query"]);
}

// Process query and print results
if ($query_str) {
    echo "Here is the query you entered:<br />" . $query_str . "<br />";
    $result = mysql_query($query_str, $db_connection);
    mysql_close($db_connection);
    displayResult($result);
}

// *****************************************************************************
// Helper Functions
// *****************************************************************************

function processQuery($text) {
    mysql_real_escape_string($text, $db_connection);
    return $text;
}

function displayResult($resource) {
    while ($row = mysql_fetch_row($resource)) {
        $mid = $row[0];
        $ticketsSold = $row[1];
        $totalIncome = $row[2];
        print "$mid, $ticketsSold, $totalIncome<br />";
    }
}
?>

</html>