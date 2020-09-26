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
</table>

<div id="listingTable"></div>
<a href="javascript:prevPage()" id="btn_prev">Prev</a>
<a href="javascript:nextPage()" id="btn_next">Next</a>
page: <span id="page"></span>

<script>
    let EVENTS = [];
    let eventsAdded = 0;

    var currentPage = 1;
    var eventsPerPage = 20;

    // Make a get request to the URL
    makeRequest("GET", "Events_Helper.php?count=All", eventCallback);

    /**
     * Function to add the events in response text to the datalist in the webpage.
     **/
    function eventCallback(responseText) {
        let eventsTable = document.getElementById('events-table');
        let parsedResponse = JSON.parse(responseText);
        fieldNames = parsedResponse[0];
        let events = parsedResponse[1];
        EVENTS = events;

        let headerRow = document.createElement('tr');
        for (let i=0; i< fieldNames.length; i++) {
            let tableHeader = document.createElement('th');
            tableHeader.innerHTML = fieldNames[i];
            headerRow.appendChild(tableHeader);
        }
        eventsTable.appendChild(headerRow);
        for (let i=0; i<eventsPerPage * fieldNames.length; i += fieldNames.length) {
            let tableRow = document.createElement('tr');
            tableRow.className = "event";
            for (let j = 0; j < fieldNames.length; j++){
                let tableData = document.createElement('td');
                tableData.innerHTML = events[i+j];
                tableRow.appendChild(tableData);
            }
            eventsTable.appendChild(tableRow);
        }
    }

    function nextPage() {
        // Delete all events in the table
        var totalPages = Math.ceil((EVENTS.length / fieldNames.length) / eventsPerPage);
        if (currentPage + 1 <= totalPages) {
            currentPage++;
            var events = document.getElementsByClassName('event');
            for (var i = events.length - 1; i >= 0; i--) {
                events[i].remove();
            }

            let eventsTable = document.getElementById('events-table');
            for (let i = (currentPage - 1) * eventsPerPage * fieldNames.length; i < currentPage * eventsPerPage * fieldNames.length; i += fieldNames.length) {
                let tableRow = document.createElement('tr');
                tableRow.className = "event";
                for (let j = 0; j < fieldNames.length; j++) {
                    let tableData = document.createElement('td');
                    tableData.innerHTML = EVENTS[i + j];
                    tableRow.appendChild(tableData);
                }
                eventsTable.appendChild(tableRow);
            }
        }
    }

    function prevPage() {
        // Delete all events in the table
        if (currentPage - 1 > 0) {
            currentPage--;
            var events = document.getElementsByClassName('event');
            for (var i = events.length - 1; i >= 0; i--) {
                events[i].remove();
            }

            let eventsTable = document.getElementById('events-table');
            for (let i = (currentPage - 1) * eventsPerPage * fieldNames.length; i < currentPage * eventsPerPage * fieldNames.length; i += fieldNames.length) {
                let tableRow = document.createElement('tr');
                tableRow.className = "event";
                for (let j = 0; j < fieldNames.length; j++) {
                    let tableData = document.createElement('td');
                    tableData.innerHTML = EVENTS[i + j];
                    tableRow.appendChild(tableData);
                }
                eventsTable.appendChild(tableRow);
            }
        }
    }

</script>
</body>
</html>

