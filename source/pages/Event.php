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

        <title id="title">Event</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8">
    </head>
<body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
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

    <!-- Event title -->
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-auto">
                <h1 id="event-name"></h1>
            </div>
            <div class="w-100"></div>
            <div class="col-md-auto">
                <h2 id="event-date"></h2>
            </div>
        </div>
    </div>

    <br> <br> <br>

    <!-- Actions table -->
    <div class="container">
        <table id="actions-table" class="table table-hover">
            <thead id="action-headings" class="thead-dark"></thead>
            <tbody id="action-body"></tbody>
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
        // Gets event details from the URL
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        let event_id = urlParams.get('event_id');
        let date = urlParams.get('date');

        let HEADER = false;
        var actionsPerPage = 20;

        // Make a get request to the URL
        makeRequest("GET", "Event_Helper.php?event_id=" + event_id + "&date=" + date, pagination);


        function pagination(responseText) {
            /**
             * Creates the actions table header and then uses jquery pagination to
             * call structureDataTable() which then adds the action data to the table
             * @param responseText is the response from the query to get all actions for the event
             */
            let actionsTable = document.getElementById('action-headings');
            let parsedResponse = JSON.parse(responseText);
            let actions = parsedResponse[1];
            let eventName = actions[0][0];
            let eventDate = actions[0][1];

            document.getElementById('event-name').innerHTML = eventName;
            document.getElementById('event-date').innerHTML = eventDate;
            document.getElementById('title').innerHTML = eventName + " " + eventDate;

            if (HEADER === false) {
                let headerRow = document.createElement('tr');
                for (let i=0; i<4; i++) {
                    let tableHeader = document.createElement('th');
                    switch (i) {
                        case 0:
                            tableHeader.innerHTML = "Cluster Name";
                            break;
                        case 1:
                            tableHeader.innerHTML = "Machine Group";
                            break;
                        case 2:
                            tableHeader.innerHTML = "Time";
                            break;
                        case 3:
                            tableHeader.innerHTML = "Activation";
                            break;
                        default:
                            break;
                    }
                    headerRow.appendChild(tableHeader);
                }
                actionsTable.appendChild(headerRow);
                HEADER = true;
            }

            $('#pagination-container').pagination({
                dataSource: actions,
                pageSize: actionsPerPage,
                callback: function(data, pagination) {
                    structureDataTable(data);
                }
            })
        }

        function structureDataTable(data) {
            /**
             * Adds all the actions data to the actions table
             * @param data
             */
            let actionsTable = document.getElementById('action-body');
            let actions = document.getElementsByClassName('action');
            for (let i = actions.length - 1; i >= 0; i--) {
                actions[i].remove();
            }
            for (let i = 0; i < data.length; i++) {
                let tableRow = document.createElement('tr');
                tableRow.className = 'action';
                for (let j = 2; j < data[i].length; j++) {
                    let tableData = document.createElement('td');
                    if (j === data[i].length - 1) { //The activation field
                        if (data[i][j] === 0){
                            tableData.innerHTML = "OFF";
                            tableData.className = "btn btn-danger btn-sm";
                        } else {
                            tableData.innerHTML = "ON";
                            tableData.className = "btn btn-success btn-sm";
                        }
                    } else {
                        tableData.innerHTML = data[i][j];
                    }
                    tableRow.appendChild(tableData);
                }
                actionsTable.appendChild(tableRow);
            }
        }

    </script>
</body>
</html>

