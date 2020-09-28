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

    <br > <br > <br >

    <table id="events-table"></table>
    <nav id="pagination-container" class="pagination"></nav>

    <div id="listingTable"></div>
    <a href="javascript:prevPage()" id="btn_prev">Prev</a>
    <a href="javascript:nextPage()" id="btn_next">Next</a>
    page: <span id="page"></span>

<script>
    let EVENTS = [];

    var currentPage = 1;
    var eventsPerPage = 20;

    // Make a get request to the URL
    makeRequest("GET", "Events_Helper.php?count=All", pagination);

    function pagination(responseText) {
        let eventsTable = document.getElementById('events-table');
        let parsedResponse = JSON.parse(responseText);
        let fieldNames = parsedResponse[0];
        let actions = parsedResponse[1];
        let headerRow = document.createElement('tr');
        for (let i=0; i< fieldNames.length; i++) {
            let tableHeader = document.createElement('th');
            tableHeader.innerHTML = fieldNames[i];
            headerRow.appendChild(tableHeader);
        }
        eventsTable.appendChild(headerRow);
        $('#pagination-container').pagination({
            dataSource: actions,
            pageSize: 20,
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

