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

<h1> Events </h1>

<br > <br > <br >

<table id="events-table">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Status</th>
    </tr>
</table>

<script>
    let EVENTS = [];
    let eventsAdded = 0;

    // Make a get request to the URL
    makeRequest("GET", "Events_Helper.php?count=All", eventCallback);

    /**
     * Function to add the events in response text to the datalist in the webpage.
     **/
    function eventCallback(responseText) {
        let eventsTable = document.getElementById('events-table');
        console.log(responseText);
        let events = JSON.parse(responseText);
        EVENTS = events;
        for (let i=0; i<events.length; i += 3) {
            let tableRow = document.createElement('tr');
            let eventId = document.createElement('td');
            let eventName = document.createElement('td');
            let status = document.createElement('td');
            eventId.innerHTML = events[i];
            eventName.innerHTML = events[i+1];
            status.innerHTML = events[i+2];
            tableRow.appendChild(eventId);
            tableRow.appendChild(eventName);
            tableRow.appendChild(status);
            eventsTable.appendChild(tableRow);
        }
    }
</script>
</body>
</html>

