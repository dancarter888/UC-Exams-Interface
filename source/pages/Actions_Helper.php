<?php
require_once("../config/config.php");

if (isset($_GET['start']) && isset($_GET['end']))
{
    $start_date = $_GET['start'];
    $end_date = $_GET['end'];
    if (strtotime($start_date) !== false && strtotime($end_date) !== false) {
        $get_actions = getActions($start_date, $end_date);
        $field_names = $get_actions[0];
        $actions = $get_actions[1];
        echo json_encode([$field_names, $actions]);
    }
}

if (isset($_GET['dates']))
{
    $dates = getDates();
    echo json_encode($dates);
}

function getActions($start_date, $end_date) {
    $result = queryDB($start_date, $end_date);
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

function getDates() {
    $hostname = "127.0.0.1";
    $database = "tserver";
    $username = "root";
    $password = "mysql";
    $conn = new mysqli($hostname, $username, $password, $database);

    $query = "CALL show_dates()";
    $result = $conn->query($query);

    while ($row = $result->fetch_row()) {
        $rows[] = $row;
    }

    return $rows;
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

function queryDB($start_date, $end_date) {
    $hostname = "127.0.0.1";
    $database = "tserver";
    $username = "root";
    $password = "mysql";
    $conn = new mysqli($hostname, $username, $password, $database);

    $query = "CALL show_actions(?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $start_date, $end_date);
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