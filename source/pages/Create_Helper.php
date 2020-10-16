<?php
/**
 * A file for handling POST and GET requests associated with creating events.
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

// The type of requests that can be handled
$ITEMS = array("Rooms", "Clusters");

// Check if it is a GET or POST request
if (isset($_GET['item']))
{
    // Check if the request is for rooms or clusters
    if ($_GET['item'] === $ITEMS[0]) {
        $rooms = getRooms($conn);
        echo json_encode($rooms);
    } else if ($_GET['item'] === $ITEMS[1]) {
        $clusters = getClusters($conn);
        echo json_encode($clusters);
    }
}
else
{
    // Check if the post request is for creating events or actions
    if (isset($_POST["event"])) {
        createEvent($conn);
    } else {
        add_action($conn);
    }

}

/**
 * Creates a query from the decoded post request for an event and queries the database with it. The rooms array needs to
 * be formatted into a string and the date split up into day, week and year number.
 *
 * @param $conn connection the database connection
 */
function createEvent($conn) {
    $event = $_POST["event"];
    // Associative array of the JSON
    $decoded = json_decode($event, true);

    //Create Query
    $qName = $decoded["Name"];
    $qRooms = "";
    foreach ($decoded["Rooms"] as $room) {
        $qRooms = $qRooms . "{$room}";
        if ($room != end($decoded["Rooms"])) {
            $qRooms = $qRooms . ",";
        }
    }
    $startTime = $decoded["StartTime"];

    $qDate = $decoded["Date"];
    $qDay = idate('w', strtotime($qDate));
    $qWeek = idate('W', strtotime($qDate));
    $qYear = idate('Y', strtotime($qDate));

    // Call stored procedure
    $query = "CALL add_event('{$qName}','{$qRooms}',{$qDay},{$qWeek},{$qYear},'{$startTime}')";
    $result = queryDB($conn, $query);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo $row["front_ID"];
        }
    } else {
        echo "0 results";
    }
}

/**
 * Creates a query from the decoded post request for an action and queries the database with it.
 *
 * @param $conn connection the database connection
 */
function add_action($conn) {
    $encoded_action = $_POST["action"];
    $action = json_decode($encoded_action, true);
    $time = strtotime($action["Time"]) - strtotime($action["StartTime"]);
    if ($time < 0) {
        $time_diff = date('-H:i:s', -1 * $time);
    } else {
        $time_diff = date('H:i:s', $time);
    }

    $query = "CALL add_action({$action["EventID"]}, '{$time_diff}', '{$action["ClusterName"]}', {$action["Activation"]});";
    $result = queryDB($conn, $query);
}

/**
 * Queries the database for a list of the rooms that test can happen in.
 *
 * @param $conn connection the database connection
 * @return array and array of the names of the rooms
 */
function getRooms($conn) {
    $result = queryDB($conn, "CALL show_rooms;");
    $rooms = array();
    foreach ($result as $row) {
        array_push($rooms, $row["room_name"]);
    }

    return $rooms;
}

/**
 * Queries the database for a list of the different clusters, their id and a
 * description of them
 *
 * @return array an array where the elements of form [cluster_id, cluster_name, cluster_description].
 */
function getClusters($conn) {

    $result = queryDB($conn, "CALL get_front_clusters;");
    $clusters = array();
    foreach ($result as $row) {
        array_push($clusters, array($row["cluster_id"], $row["cluster_name"], $row["cluster_description"]));
    }

    return $clusters;
}

function queryDB($conn, $query) {
    return $conn->query($query);
}

$conn->close();
?>