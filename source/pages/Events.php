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

    <form>
        <h3>Search</h3>
        <input type="text" size="30" onkeyup="showResult(this.value)">
        <div id="livesearch"></div>
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
        if (HEADER === false) {
            let headerRow = document.createElement('tr');
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
            }
        })
    }

    function structureDataTable(data) {
        let actionsTable = document.getElementById('events-table');
        let events = document.getElementsByClassName('event');
        for (let i = events.length - 1; i >= 0; i--) {
            events[i].remove();
        }
        for (let i = 0; i < data.length; i++) {
            let tableRow = document.createElement('tr');
            tableRow.className = 'event';
            for (let j = 0; j < data[i].length; j++) {
                let tableData = document.createElement('td');
                if (j === 0) { // If the data is the event name
                    let url = "Actions.php?event_id=" + data[i][8] + "&cluster_id=" + data[i][2];
                    tableData.innerHTML = "<a href=" + url + ">" + data[i][j] + "</a>";
                } else {
                    tableData.innerHTML = data[i][j];
                }
                tableRow.appendChild(tableData);
            }
            actionsTable.appendChild(tableRow);
        }
    }

    function showResult(str) {
        if (str.length == 0) {
            document.getElementById("livesearch").innerHTML="";
            document.getElementById("livesearch").style.border="0px";
            return;
        }
        let url = "Events_Helper.php?q=" + str;
        console.log(url);
        $.ajax({
            url: url,
            success: function(result) {
                document.getElementById("livesearch").innerHTML = this.responseText;
                document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
            }
        });
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
