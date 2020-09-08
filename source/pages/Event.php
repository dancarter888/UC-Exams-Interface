<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event</title>
</head>
<body>

</body>
<?php

require_once("../config/config.php");

$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error)
{
    fatalError($conn->connect_error);
    return;
}

#TODO get event_id from Events.php

$event_id = 1;

$stmt = $conn->prepare("CALL get_one_event_details(?);");
$stmt->bind_param('i', $event_id);
$stmt->execute();

if ($stmt->errno) {
    fatalError($stmt->error);
} else {
    echo "<table style='width:100%'><tr><th>Event Name</th><th>Time Offset</th><th>Cluster Name</th><th>Activation</th></tr>";

    $result = $stmt->get_result();
    for($i = 0; $i < $result->num_rows; $i++) {
        $action = $result->fetch_assoc();
        #TODO need to add dates of event
        echo "<tr>";
        echo "<th>" . $action['event_name'] . "</th>";
        echo "<th>" . $action['time_offset'] . "</th>";
        echo "<th>" . $action['cluster_name'] . "</th>";
        echo "<th>" . $action['activate'] . "</th>";
        echo "</tr>";
    }
    echo "</table>";


}

$conn->close();



