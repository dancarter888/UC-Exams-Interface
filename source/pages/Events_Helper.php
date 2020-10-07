<?php
require_once("../config/config.php");
$conn = new mysqli($hostname, $username, $password, $database);

if (isset($_GET['start']) && isset($_GET['end']) || isset($_GET['q']))
{
    $start_date = $_GET['start'];
    if ($start_date === "today") {
        $start_date = date("Y-m-d");
    }
    $end_date = $_GET['end'];
    $query_string = $_GET['q'];
    if (strtotime($start_date) !== false && strtotime($end_date) !== false) {
        $get_events = getEvents($conn, $start_date, $end_date, $query_string);
        $field_names = $get_events[0];
        $events = $get_events[1];
        echo json_encode([$field_names, $events]);
    }
}

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
 * @param $var
 * @return string
 */
function sanitizeString($var) {
    if (get_magic_quotes_gpc())
        $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}
?>