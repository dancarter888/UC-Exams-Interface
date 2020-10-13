<?php
/*
Deletes cookie if it is set.
Used for logout, when logout is clicked on another page it
redirects to this page and then deletes the cookie.
*/
if (!isset($_COOKIE['loggedin'])) {
    header("Location: Login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- CSS -->
        <link rel="stylesheet" href="../css/Pagination.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!-- JavaScript -->
        <script src="../js/AJAX.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="../js/Pagination.js"></script>

        <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8">
        <title>Events</title>
    </head>
    <body>
        <!-- Navigation bar -->
        <nav class="navbar navbar-expand navbar-light bg-light">
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
                    <a class="btn btn-outline-danger" href="Login.php" role="button">Logout</a>
                </form>
            </div>
        </nav>

        <!-- Title -->
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-auto">
                    <h1> Events </h1>
                </div>
            </div>
        </div>

        <!-- Date Filter and Search -->
        <div class="container">
            <form id="date-filter">
                <div class="row align-items-end justify-content-between">
                    <div class="col-3">
                        <h5>Start Date</h5>
                        <input class="form-control" type="date" id="start-dates" required>
                    </div>
                    <div class="col-3">
                        <h5>End Date</h5>
                        <input class="form-control" type="date" id="end-dates" required>
                    </div>
                    <div class="col-1">
                        <input class="form-control" type="submit" value="Filter"/>
                    </div>
                    <div class="col-2">
                    </div>
                    <div class="col">
                        <form>
                            <h5>Search</h5>
                            <input class="form-control" type="text" size="30" onkeyup="showResult(this.value)">
                            <div id="livesearch"></div>
                        </form>
                    </div>
                </div>
            </form>
        </div>

        <br/>

        <!-- Events table -->
        <div class="container">
            <table id="events-table" class="table table-hover">
                <thead id="events-headings" class="thead-dark"></thead>
                <tbody id="events-body"></tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-auto">
                    <nav id="pagination-container" class="pagination"></nav>
                </div>
            </div>
        </div>

        <script>
            // Checks the user has the has the correct token
            makeRequest("GET", "Authenticate.php?token=" + window.localStorage.getItem('token'), function(response) {
                if (response !== "True") {
                    window.location.href = window.location.href.replace("Events.php", "Login.php");
                }
            });

            // Initialised to include all events
            let STARTDATE = "1995-01-01";
            let ENDDATE = "9999-12-31";
            let QUERYSTRING = "";
            let HEADER = false;

            var eventsPerPage = 20;

            // Make a get request to the URL to get events and add them to the html table
            makeRequest("GET", "Events_Helper.php?start=" + STARTDATE + "&end=" + ENDDATE + "&q=" + QUERYSTRING, pagination);

            /**
             * Formats events so they can be added to the table and used for pagination
             * @return list of events
             */
            function reformatEvents(events) {
                console.log(events);
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
                    } else if (currentEventName === prevEventName && currentEventDate === prevEventDate) {
                        prevEventEndTime = currentEventTime;
                    } else if ((currentEventName !== prevEventName || currentEventDate !== prevEventDate) || i === events.length - 1) {
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
                /**
                 * Creates the events table header and then uses jquery pagination to
                 * call structureDataTable() which then adds the event data to the table
                 * @param responseText is the response from the query to get all events
                 */
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
                /**
                 * Adds all the events data to the events table
                 * @param data
                 */
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

            function showResult(str) {
                /**
                 * Called when the user types into the search box and updates the events in the
                 * events table based on the searched string
                 */
                QUERYSTRING = str;
                let url = "Events_Helper.php?start=" + STARTDATE + "&end=" + ENDDATE + "&q=" + QUERYSTRING;
                console.log(url);

                makeRequest("GET", "Events_Helper.php?start=" + STARTDATE + "&end=" + ENDDATE + "&q=" + QUERYSTRING, pagination);
            }

            // Updates the event data when the filter button pressed to filter events based on datas
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

