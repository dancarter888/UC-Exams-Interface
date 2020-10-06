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

    <title id="title">Event</title>
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

<h1 id="event-name"></h1>
<h2 id="event-date"></h2>

<br> <br> <br>


<table id="actions-table">
</table>
<nav id="pagination-container" class="pagination"></nav>

<script>
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    let event_id = urlParams.get('event_id');
    let date = urlParams.get('date');

    let HEADER = false;
    var actionsPerPage = 20;

    // Make a get request to the URL
    makeRequest("GET", "Event_Helper.php?event_id=" + event_id + "&date=" + date, pagination);


    function pagination(responseText) {
        let eventsTable = document.getElementById('actions-table');
        let parsedResponse = JSON.parse(responseText);
        let fieldNames = parsedResponse[0];
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
            eventsTable.appendChild(headerRow);
            HEADER = true;
        }

        $('#pagination-container').pagination({
            dataSource: actions,
            pageSize: actionsPerPage,
            callback: function(data, pagination) {
                structureDataTable(data);
                console.log(pagination);
            }
        })
    }

    function structureDataTable(data) {
        let eventsTable = document.getElementById('actions-table');
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
                    } else {
                        tableData.innerHTML = "ON";
                    }
                } else {
                    tableData.innerHTML = data[i][j];
                }
                tableRow.appendChild(tableData);
            }
            eventsTable.appendChild(tableRow);
        }
    }



    // Basic structure but is not implemented in Actions_helper.php and tserver.sql dump
    function showResult(str) {
        if (str.length == 0) {
            document.getElementById("livesearch").innerHTML="";
            document.getElementById("livesearch").style.border="0px";
            return;
        }
        let url = "Actions_Helper.php?q=" + str;
        console.log(url);
        $.ajax({
            url: url,
            success: function(result) {
                console.log(result);
                document.getElementById("livesearch").innerHTML = this.responseText;
                document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
            }
        });
    }

    $('#date-filter').submit(function () {
        let startDate = document.getElementById("start-dates").value;
        let endDate = document.getElementById("end-dates").value;
        makeRequest("GET", "Events_Helper.php?start=" + startDate + "&end=" + endDate, pagination);
        return false;
    });
</script>
</body>
</html>

