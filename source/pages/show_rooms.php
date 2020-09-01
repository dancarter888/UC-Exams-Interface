<?php
    require_once("../config/config.php");

    if (isset($_GET['url']))
    {
        //$rooms = getRooms();
        $rooms = array();
        array_push($rooms, 'Erskine', 'Library');

        echo json_encode($rooms);
    }

    function getRooms() {
        $conn = new mysqli($hostname, $username, $password, $database);
        if ($conn->connect_error)
        {
            fatalError($conn->connect_error);
            return;
        }

        $result = $conn->query("CALL show_rooms;");
        $rooms = array();
        foreach ($result as $row) {
            array_push($rooms, $row["room_name"]);
        }

        return $rooms;
    }

    function sanitizeString($var)
    {
        $var = strip_tags($var);
        $var = htmlentities($var);
        return stripslashes($var);
    }
?>
