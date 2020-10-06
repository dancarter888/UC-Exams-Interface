<?php
require_once("../config/config.php");
$conn = new mysqli($hostname, $username, $password, $database);

if (isset($_GET['event_id']) && isset($_GET['date']))
{
    $event_id = $_GET['event_id'];
    $date = $_GET['date'];
    $get_actions = getActions($conn, $event_id, $date);
    $field_names = $get_actions[0];
    $actions = $get_actions[1];
    echo json_encode([$field_names, $actions]);
}


function getActions($conn, $event_id, $date) {
    $result = queryDB($conn, $event_id, $date);
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
    $result = queryDB("CALL show_event(?);");
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

function queryDB($conn, $event_id, $date) {
    $query = "CALL show_event(?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $event_id, $date);
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