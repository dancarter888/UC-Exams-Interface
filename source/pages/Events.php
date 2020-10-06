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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- JavaScript -->
    <script src="../js/AJAX.js"></script>
    <script src="../js/NavBar.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/pagination.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <title>Events</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8">
</head>
<body>

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <ul class="nav navbar-nav">
                <li><a href="Create.php">Create Event</a></li>
                <li class="active"><a href="Events.php"> Events </a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="login.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1> Events </h1>
    </div>

    <div class="container">
        <form id="date-filter">
            <h3>Start Date:</h3>
            <input type="date" id="start-dates" required>

            <h3>End Date:</h3>
            <input type="date" id="end-dates" required>

            <input type="submit" value="Filter"/><br />
        </form>
        <form>
            <input type="text" size="30" onkeyup="showResult(this.value)">
            <div id="livesearch"></div>
        </form>
    </div>

    <div class="container">
        <table id="events-table" class="table table-hover"></table>
    </div>
    <div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <nav id="pagination-container" class="pagination"></nav>
        </div>
    </div>
    </div>

<script>
    let STARTDATE = "1995-01-01";
    let ENDDATE = "9999-12-31";
    let QUERYSTRING = "";
    let HEADER = false;

    var eventsPerPage = 20;

    // Set the start date field to today's date
    //document.getElementById('start-dates').value = new Date().toISOString().slice(0,10);

    // Make a get request to the URL
    makeRequest("GET", "Events_Helper.php?start=" + STARTDATE + "&end=" + ENDDATE + "&q=" + QUERYSTRING, pagination);

    function reformatEvents(events) {
        let prevEventName = null;
        let prevEventStartTime = null;
        let prevEventEndTime = null;
        let prevEventDate = null;
        let prevEventID = null;
        let newEvents = [];
        for (let i = 0; i < events.length; i++) {
            let currentEventName = events[i][0];
            let currentEventTime = events[i][1];
            let currentEventDate = events[i][2];
            let currentEventID = events[i][3];

            if (prevEventName === null) {
                prevEventName = events[i][0];
                prevEventStartTime = events[i][1];
                prevEventDate = events[i][2];
                prevEventID = events[i][3];
            } else if (currentEventName === prevEventName) {
                prevEventEndTime = currentEventTime;
            } else if (currentEventName !== prevEventName || i === events.length - 1) {
                newEvents.push([prevEventName, prevEventStartTime + " - " + prevEventEndTime, prevEventDate, prevEventID]);
                prevEventName = currentEventName;
                prevEventStartTime = currentEventTime;
                prevEventDate = currentEventDate;
                prevEventID = currentEventID;
            }
        }
        return newEvents;
    }

    function pagination(responseText) {
        let eventsTable = document.getElementById('events-table');
        let parsedResponse = JSON.parse(responseText);
        let fieldNames = parsedResponse[0];
        let events = reformatEvents(parsedResponse[1]);

        if (HEADER === false) {
            let headerRow = document.createElement('tr');
            for (let i=0; i<3; i++) {
                let tableHeader = document.createElement('th');
                switch (i) {
                    case 0:
                        tableHeader.innerHTML = "Event Name";
                        break;
                    case 1:
                        tableHeader.innerHTML = "Time Range";
                        break;
                    case 2:
                        tableHeader.innerHTML = "Date";
                        break;
                    default:
                        break;
                }
                headerRow.appendChild(tableHeader);
            }
            eventsTable.appendChild(headerRow);
            HEADER = true;
        }

        $('#pagination-container').pagination({
            dataSource: events,
            pageSize: eventsPerPage,
            callback: function(data, pagination) {
                structureDataTable(data);
                console.log(pagination);
            }
        })
    }

    function structureDataTable(data) {
        let eventsTable = document.getElementById('events-table');
        let events = document.getElementsByClassName('event');
        for (let i = events.length - 1; i >= 0; i--) {
            events[i].remove();
        }
        for (let i = 0; i < data.length; i++) {
            let tableRow = document.createElement('tr');
            tableRow.className = 'event';
            for (let j = 0; j < data[i].length - 1; j++) {
                let tableData = document.createElement('td');
                if (j === 0) { // If the data is the event name
                    let url = "Event.php?event_id=" + data[i][3] + "&date=" + data[i][2];
                    tableData.innerHTML = "<a href=" + url + ">" + data[i][j] + "</a>";
                } else {
                    tableData.innerHTML = data[i][j];
                }
                tableRow.appendChild(tableData);
            }
            eventsTable.appendChild(tableRow);
        }
    }



    //Search functionality
    function showResult(str) {
        QUERYSTRING = str;
        let url = "Events_Helper.php?start=" + STARTDATE + "&end=" + ENDDATE + "&q=" + QUERYSTRING;
        console.log(url);

        makeRequest("GET", "Events_Helper.php?start=" + STARTDATE + "&end=" + ENDDATE + "&q=" + QUERYSTRING, pagination);
    }

    $('#date-filter').submit(function () {
        let startDate = document.getElementById("start-dates").value;
        STARTDATE = startDate;
        let endDate = document.getElementById("end-dates").value;
        ENDDATE = endDate;
        makeRequest("GET", "Events_Helper.php?start=" + STARTDATE + "&end=" + ENDDATE + "&q=" + QUERYSTRING, pagination);
        return false;
    });
</script>
</body>
</html>

