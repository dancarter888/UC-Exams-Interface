<?php
if (!isset($_COOKIE['loggedin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- CSS -->
    <link rel="stylesheet" href="../css/NavBar.css">
    <link rel="stylesheet" href="../css/pagination.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">


    <!-- JavaScript -->
    <script src="../js/AJAX.js"></script>
    <script src="../js/NavBar.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/pagination.js"></script>

    <title>Events</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="Create.php">Create Event </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="Events.php">Events <span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <a class="btn btn-outline-danger" href="login.php" role="button">Logout</a>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-auto">
                <h1> Events </h1>
            </div>
        </div>
    </div>

    <div class="container">
        <form id="date-filter">
            <div class="row align-items-end justify-content-between">
                <div class="col-2">
                    <h5>Start Date</h5>
                </div>
                <div class="col">
                    <h5>End Date</h5>
                </div>
                <div class="w-100"></div>
                <div class="col-2">
                    <input type="date" id="start-dates" required>
                </div>
                <div class="col-2">
                    <input type="date" id="end-dates" required>
                </div>
                <div class="col">
                    <input type="submit" value="Filter"/><br />
                </div>
                <div class="w-100"></div>
                <form>
                    <div class="col-1">
                        <h5>Search</h5>
                    </div>
                    <div class="w-100"></div>
                    <div class="col">
                        <input type="text" size="30" onkeyup="showResult(this.value)">
                    </div>
                </form>
            </div>
        </form>
    </div>

    <br/>

    <div class="container">
        <table id="events-table" class="table table-hover">
            <thead id="events-headings" class="thead-dark"></thead>
            <tbody id="events-body"></tbody>
        </table>
    </div>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-auto">
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
        let eventsTable = document.getElementById('events-headings');
        let parsedResponse = JSON.parse(responseText);
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
                tableHeader.scope = "col";
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
            }
        })
    }

    function structureDataTable(data) {
        let eventsTable = document.getElementById('events-body');
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

