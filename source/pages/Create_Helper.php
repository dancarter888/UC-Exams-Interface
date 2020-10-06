<?php
    require_once("../config/config.php");

    $ITEMS = array("Rooms", "Clusters");

    if (isset($_GET['item']))
    {
        if ($_GET['item'] === $ITEMS[0]) {
            $rooms = getRooms();
            echo json_encode($rooms);
        } else if ($_GET['item'] === $ITEMS[1]) {
            $clusters = getClusters();
            echo json_encode($clusters);
        }
    }
    else
    {
        if (isset($_POST["event"])) {
            createEvent();
        } else {
            add_action();
        }

    }

    function createEvent() {
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
        //echo "{$qDay} - {$qWeek} - {$qYear}";

        // Call stored procedure
        $query = "CALL add_event('{$qName}','{$qRooms}',{$qDay},{$qWeek},{$qYear},'{$startTime}')";
        //$result = queryDB($query);
        //$result = queryDB('call add_event("EMTH119-20S2 Tuesday", "Erskine-033,Erskine-035,Erskine-036,Erskine-038", "MapleTA", 2, 34, 2020, "18:00:00", "01:00:00");');
        //echo count($result);
//        $eventId = 0;
//        foreach ($result as $row) {
//            $eventId = $row["front_ID"];
//        }
//        echo $eventId;
        echo $query;
    }

    function add_action() {
        $encoded_action = $_POST["action"];
        $action = json_decode($encoded_action, true);
        $time = strtotime($action["Time"]) - strtotime($action["StartTime"]);
        if ($time < 0) {
            $time_diff = date('-H:i:s', -1 * $time);
        } else {
            $time_diff = date('H:i:s', $time);
        }

        $query = "CALL add_action({$action["EventID"]}, '{$time_diff}', '{$action["ClusterName"]}', {$action["Activation"]});";
        echo $query;
//        $result = queryDB($query);
//
//        // Not sure about this
//        $action_ids = array();
//        foreach ($result as $row) {
//            array_push($action_ids, $row["action_id"]);
//        }
//
//        if ($action_ids[0] == null) {
//            echo "Success";
//        } else {
//            echo "Fail";
//        }
    }

    /**
     * Queries the database for a list of the rooms that test can happen in.
     * @return array and array of the names of the rooms
     */
    function getRooms() {
        $result = queryDB("CALL show_rooms;");
        $rooms = array();
        foreach ($result as $row) {
            array_push($rooms, $row["room_name"]);
        }

        return $rooms;
    }

    /**
     * Queries the database for a list of the different clusters, their id and a
     * description of them
     * @return array an array where the elements of form [cluster_id, cluster_name, cluster_description].
     */
    function getClusters() {

        $result = queryDB("CALL get_front_clusters;");
        $clusters = array();
        foreach ($result as $row) {
            array_push($clusters, array($row["cluster_id"], $row["cluster_name"], $row["cluster_description"]));
        }

        return $clusters;
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

    function sanitizeString($var)
    {
        $var = strip_tags($var);
        $var = htmlentities($var);
        return stripslashes($var);
    }
?>
