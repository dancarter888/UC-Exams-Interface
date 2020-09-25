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
    <a href="Events.php"> Events </a>
    <br> <br> <br>
    <a href="login.php">Logout</a>
</div>

<div id="main">
    <button class="openbtn" onclick="openNav()"> ☰ </button>
</div>

<h1> Events </h1>

<br > <br > <br >

<table id="events-table">
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
        let parsedResponse = JSON.parse(responseText);
        let fieldNames = parsedResponse[0];
        let events = parsedResponse[1];
        EVENTS = events;

        let headerRow = document.createElement('tr');
        for (let i=0; i< fieldNames.length; i++) {
            let tableHeader = document.createElement('th');
            tableHeader.innerHTML = fieldNames[i];
            headerRow.appendChild(tableHeader);
        }
        eventsTable.appendChild(headerRow);

        for (let i=0; i<events.length; i += fieldNames.length) {
            let tableRow = document.createElement('tr');
            for (let j = 0; j < fieldNames.length; j++){
                let tableData = document.createElement('td');
                tableData.innerHTML = events[i+j];
                tableRow.appendChild(tableData);
            }
            eventsTable.appendChild(tableRow);
        }
    }
</script>
</body>
</html>

