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

if (isset($_GET['q'])) {
    $get_actions = getSearchedActions($_GET['q']);
    $field_names = $get_actions[0];
    $actions = $get_actions[1];
    echo json_encode([$field_names, $actions]);
}

/**
 * Queries the database for a list of the events.
 * @return array an array of the event_id, event_name and status
 */
function getActions() {
    $result = queryDB("CALL show_actions;");
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

function getSearchedActions($search) {
    $result = queryDB("CALL search_actions(?);");
    $field_names = [];
    $rows = [];
    echo $result;
    while ($field = $result->fetch_field()) {
        $field_names[] = $field->name;
    }
    while ($row = $result->fetch_row()) {
        $rows[] = $row;
    }
    return [$field_names, $rows];
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