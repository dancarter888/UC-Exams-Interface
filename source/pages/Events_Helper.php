<?php
/**
 * A file for handling POST and GET requests associated with the events page.
 */

require_once("../config/config.php");

// Creates a connection to the database using variables from the config file
$conn = new mysqli($hostname, $username, $password, $database);

// Catches any error connecting to the database
if ($conn->connect_error)
{
    fatalError($conn->connect_error);
    return;
}

// Checks variables have been set, sanitizes the search string and then calls getEvents()
if (isset($_GET['start']) && isset($_GET['end']) || isset($_GET['q']))
{
    $start_date = $_GET['start'];
    if ($start_date === "today") {
        $start_date = date("Y-m-d"); // Updates start_date to todays date
    }
    $end_date = $_GET['end'];
    $query_string = sanitizeString($_GET['q']);
    if (strtotime($start_date) !== false && strtotime($end_date) !== false) {
        $get_events = getEvents($conn, $start_date, $end_date, $query_string);
        $field_names = $get_events[0];
        $events = $get_events[1];
        echo json_encode([$field_names, $events]);
    }
}

/**
 * Queries the database to retrieve events and returns the events
 * @param mysqli $conn to the database
 * @param string $start_date start date inputted by the user
 * @param string $end_date end date inputted by the user
 * @param string $query_string search string inputted by the user
 * @return array[] array of the field names and rows of event data
 */
function getEvents($conn, $start_date, $end_date, $query_string) {
    $result = queryDB($conn, $start_date, $end_date, $query_string);
    $field_names = [];
    $rows = [];
    while ($field = $result->fetch_field()) {
        $field_names[] = $field->name;
    }
    while ($row = $result->fetch_row()) {
        $rows[] = $row;
    }
    return [$field_names, $rows];
}

/**
 * Calls the stored procedure show_event with the given parameters using a prepared statement
 * @param mysqli $conn to the database
 * @param string $start_date start date inputted by the user
 * @param string $end_date end date inputted by the user
 * @param string $query_string search string inputted by the user
 * @return mixed results from the query
 */
function queryDB($conn, $start_date, $end_date, $query_string)
{
    $query = "CALL show_events(?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $start_date, $end_date, $query_string);
    $stmt->execute();
    return $stmt->get_result();
}



/**
 * Sanitizes a given string and returns it.
 * @param string $var string to be sanitized
 * @return string sanitized string
 */
function sanitizeString($var) {
    if (get_magic_quotes_gpc())
        $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}

/**
 * Echos an mysql error.
 *
 * @param string $error The error passed.
 */
function fatalError($error)
{
    $message = mysql_error();
    echo <<< _END
Something went wrong :/
<p>$error: $message</p>
_END;
}

// Closes database connection
$conn->close();
?>