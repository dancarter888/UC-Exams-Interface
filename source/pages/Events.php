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
    <link rel="stylesheet" href="../css/NavBar.css">
    <link rel="stylesheet" href="../css/pagination.css">

    <!-- JavaScript -->
    <script src="../js/AJAX.js"></script>
    <script src="../js/NavBar.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/pagination.js"></script>

    <title>Events</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8">
</head>
<body>
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <a href="Create.php">Create Event</a>
        <a href="Events.php"> Events </a>
        <a href="Actions.php"> Actions </a>
        <br> <br> <br>
        <a href="login.php">Logout</a>
    </div>

    <div id="main">
        <button class="openbtn" onclick="openNav()"> ☰ </button>
    </div>

    <h1> Events </h1>

    <form id="date-filter">
        <h3>Start Date:</h3>
        <input type="date" id="start-dates" required>

        <h3>End Date:</h3>
        <input type="date" id="end-dates" required>

        <input type="submit" value="Filter"/><br />
    </form>

    <br > <br > <br >

    <table id="events-table"></table>
    <nav id="pagination-container" class="pagination"></nav>

<script>
    let EVENTS = [];

    let STARTDATE = 'today';
    let ENDDATE = "9999-12-31";
    let HEADER = false;

    var currentPage = 1;
    var eventsPerPage = 20;

    // Set the start date field to today's date
    document.getElementById('start-dates').value = new Date().toISOString().slice(0,10);

    // Make a get request to the URL
    // makeRequest("GET", "Events_Helper.php?count=All", pagination);
    makeRequest("GET", "Events_Helper.php?start=" + STARTDATE + "&end=" + ENDDATE, pagination);


    function pagination(responseText) {
        let eventsTable = document.getElementById('events-table');
        let parsedResponse = JSON.parse(responseText);
        let fieldNames = parsedResponse[0];
        let actions = parsedResponse[1];
        let headerRow = document.createElement('tr');
        if (HEADER === false) {
            for (let i = 0; i < fieldNames.length; i++) {
                let tableHeader = document.createElement('th');
                tableHeader.innerHTML = fieldNames[i];
                headerRow.appendChild(tableHeader);
            }
            eventsTable.appendChild(headerRow);
            HEADER = true;
        }
        $('#pagination-container').pagination({
            dataSource: actions,
            pageSize: eventsPerPage,
            callback: function(data, pagination) {
                structureDataTable(data);
                console.log(pagination);
            }
        })
    }

    function structureDataTable(data) {
        let actionsTable = document.getElementById('events-table');
        let actions = document.getElementsByClassName('event');
        for (let i = actions.length - 1; i >= 0; i--) {
            actions[i].remove();
        }
        for (let i = 0; i < data.length; i++) {
            let tableRow = document.createElement('tr');
            tableRow.className = 'event';
            for (let j = 0; j < data[i].length; j++) {
                let tableData = document.createElement('td');
                tableData.innerHTML = data[i][j];
                tableRow.appendChild(tableData);
            }
            actionsTable.appendChild(tableRow);
        }
    }


    $('#date-filter').submit(function () {
        let startDate = document.getElementById("start-dates").value;
        let endDate = document.getElementById("end-dates").value;
        makeRequest("GET", "Events_helper.php?start=" + startDate + "&end=" + endDate, pagination);
        return false;
    });

</script>
</body>
</html>
