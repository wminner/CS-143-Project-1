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
<table cellspacing="10">
    <tr>
        <td>
        <form action="<?php $_PHP_SELF ?>" method="GET">
        <textarea name="query" cols="65" rows="15"><?php echo htmlspecialchars($_GET['query']); ?></textarea><br />
        <input type="submit" value="Submit"/>
        </form>
        </td>

        <td>Table reference:
            <ul><li>Movie(id, title, year, rating, company)</li>
                <li>Actor(id last, first, sex, dob, dod)</li>
                <li>Sales(mid, ticketsSold, totalIncome)</li>
                <li>Director(id, last, first, dob, dod)</li>
                <li>MovieGenre(mid, genre)</li>
                <li>MovieDirector(mid, did)</li>
                <li>MovieActor(mid, aid, role)</li>
                <li>MovieRating(mid, imdb, rot)</li>
                <li>Review(name, time, mid, rating, comment)</li>
                <li>MaxPersonID(id)</li>
                <li>MaxMovieID(id)</li>
            </ul>
        </td>
    </tr>
</table>

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
    echo "Here is the query you entered:<br />\"" . $query_str . "\"<br />";
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

function displayResult($rs) {
    $num_fields = mysql_num_fields($rs);
    $num_rows = mysql_num_rows($rs);
    echo "<h2>Results:</h2>";
    echo "Found " . $num_rows . " result(s).<br /><br />";

    if (mysql_num_rows($rs) > 0) {
        // BEGIN table header
        echo "<table border=1 cellspacing=2 cellpadding=2><tr>";
        for ($i=0; $i < $num_fields; $i++)
            echo "<th>" . mysql_fetch_field($rs)->name . "</th>";
        echo "</tr>";
        // END table header

        // BEGIN data rows
        while ($row = mysql_fetch_row($rs)) {
            echo "<tr>";
            for ($j=0; $j < $num_fields; $j++) {
                if (is_null($row[$j]))
                    echo "<td>N/A</td>";
                else
                    echo "<td>" . $row[$j] . "</td>";
            }
            echo "</tr>";
        }
        // END data rows
        echo "</table>";
    }
    mysql_free_result($rs);
}
?>

</html>