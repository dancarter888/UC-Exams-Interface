<?php
//if (!isset($_COOKIE['loggedin'])) {
//    header("Location: login.php");
//    exit();
//}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- CSS -->
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/NavBar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- JavaScript -->
    <script src="../js/AJAX.js"></script>
    <script src="../js/NavBar.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>Create</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8">
</head>
<body>
<div id="mySidebar" class="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
    <a href="Create.php">Create Event</a>
    <br> <br> <br>
    <a href="login.php">Logout</a>
</div>

<div id="main">
    <button class="openbtn" onclick="openNav()"> ☰ </button>
</div>

<h1> Event </h1>

<br > <br > <br >

<table id="event-table">
    <tr>
        <th>Event Name</th>
        <th>Time Offset</th>
        <th>Cluster ID</th>
        <th>Activation</th>
        <th>Cluster Name</th>
        <th>Cluster Description</th>
</table>

<script>
    let ACTIONS = [];
    let eventsAdded = 0;

    // Make a get request to the URL
    makeRequest("GET", "Event_Helper.php?id=10", eventCallback);

    /**
     * Function to add the event in response text to the datalist in the webpage.
     **/
    function eventCallback(responseText) {
        let eventTable = document.getElementById('event-table');
        let actions = JSON.parse(responseText);
        ACTIONS = actions;

        for (let i=0; i<actions.length; i += 1) {
            let action = actions[i];
            let tableRow = document.createElement('tr');
            for (let key in action) {
                let tableData = document.createElement('td');
                tableData.innerHTML = action[key];
                tableRow.appendChild(tableData);
                eventTable.appendChild(tableRow);
            }
        }
    }
</script>
</body>
</html>