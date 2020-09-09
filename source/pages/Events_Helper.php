<?php
require_once("../config/config.php");

if (isset($_GET['count']))
{
    if ($_GET['count'] === "All") {
        $events = getEvents();
        echo json_encode($events);
    }
}

/**
 * Queries the database for a list of the events.
 * @return array an array of the event_id, event_name and status
 */
function getEvents() {
    $result = queryDB("CALL show_events;");
    $events = array();
    foreach ($result as $row) {
        array_push($events, $row["event_id"], $row["event_name"], $row["status"]);
    }
    return $events;
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