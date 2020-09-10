<?php
require_once("../config/config.php");

if (isset($_GET['count']))
{
    if ($_GET['count'] === "All") {
        $get_events = getEvents();
        $field_names = $get_events[0];
        $events = $get_events[1];
        echo json_encode([$field_names, $events]);
    }
}

/**
 * Queries the database for a list of the events.
 * @return array an array of the event_id, event_name and status
 */
function getEvents() {
    $result = queryDB("CALL show_events;");
    $field_names = [];
    while ($field = $result->fetch_field()) {
        $field_names[] = $field->name;
    }

    $events = array();
    foreach ($result as $row) {
        for ($i = 0; $i < sizeof($field_names); $i++) {
            array_push($events, $row[$field_names[$i]]);
        }
    }
    return [$field_names, $events];
}

function queryDB($query) {
    $hostname = "127.0.0.1";
    $database = "tserver";
    $username = "root";
    $password = "mysql";
    $conn = new mysqli($hostname, $username, $password, $database);
    if ($conn->connect_error)
    {
        fatalError($conn->connect_error);
        return;
    }

    return $conn->query($query);
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