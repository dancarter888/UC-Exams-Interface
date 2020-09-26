<?php
require_once("../config/config.php");

if (isset($_GET['count']))
{
    if ($_GET['count'] === "All") {
        $get_actions = getActions();
        $field_names = $get_actions[0];
        $actions = $get_actions[1];
        echo json_encode([$field_names, $actions]);
    }
}

/**
 * Queries the database for a list of the events.
 * @return array an array of the event_id, event_name and status
 */
function getActions() {
    $result = queryDB("CALL show_actions;");
    $field_names = [];
    while ($field = $result->fetch_field()) {
        $field_names[] = $field->name;
    }

    $actions = array();
    foreach ($result as $row) {
        for ($i = 0; $i < sizeof($field_names); $i++) {
            array_push($actions, $row[$field_names[$i]]);
        }
    }
    return [$field_names, $actions];
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