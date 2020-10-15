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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

    <title id="title">Event</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8">
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
            <li class="nav-item">
                <a class="nav-link" href="Events.php">Events </a>
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
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" onclick="fillEditModal()" data-toggle="modal" data-target="#editActionsModalCenter">
        Edit Actions
    </button>
</div>

<!-- Pagination -->
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-auto">
            <nav id="pagination-container" class="pagination"></nav>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editActionsModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Actions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="edit-table" class="table table-hover">
                    <tbody id="edit-body">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<script>
    // Checks the user has the has the correct token
    makeRequest("GET", "Authenticate.php?token=" + window.localStorage.getItem('token'), function(response) {
        if (response !== "True") {
            window.location.href = window.location.href.replace("Event.php", "Login.php");
        }
    });

    // Gets event details from the URL
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    let event_id = urlParams.get('event_id');
    let date = urlParams.get('date');

    let HEADER = false;
    var actionsPerPage = 20;

    // Variables for the edit/delete actions functionality
    let actions = [];
    let startTime = "";

    // Make get requests to event_helper
    makeRequest("GET", "Event_Helper.php?event_id=" + event_id + "&date=" + date, pagination);

    makeRequest("GET", "Event_Helper.php?event_id=" + event_id + "&date=" + date + "&distinct=" + true, saveActions);

    makeRequest("GET", "Event_Helper.php?event_id=" + event_id + "&date=" + date + "&start=" + true, saveStartTime);


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

    function saveActions(data) {
        actions = JSON.parse(data)[1];
        for (let i=0; i<actions.length; i++) {
            let temp = actions[i][1];
            actions[i][1] = actions[i][2]
            actions[i][2] = temp;
        }
    }

    function saveStartTime(data) {
        console.log(data);
        startTime = data.slice(1, -4);
        console.log(startTime);
    }

    function fillEditModal() {
        $("#edit-body tr").remove();
        $("#edit-body div").remove();
        $(".modal-footer div").remove();
        $(".modal-footer").append(`
                <div class="container">
                    <div class="row justify-content-center">
                        <button type="button" class="btn btn-success" onclick="fillEditAddModal()">+ Add</button>
                    </div>
                </div>`);
        $("#edit-body").append('<tr> <th> Action id </th> <th> Cluster Name </th> <th> Time </th> <th> Activation </th> </tr>');
        let editTable = document.getElementById('edit-body');
        for (let i = 1; i < actions.length; i++) {
            let tableRow = document.createElement('tr');
            tableRow.className = 'action';
            for (let j = 0; j < actions[i].length; j++) {
                let tableData = document.createElement('td');
                tableData.innerHTML = actions[i][j];

                tableRow.appendChild(tableData);
            }
            let button = document.createElement('td');
            let deleteButton = document.createElement('button');
            deleteButton.innerText = "Delete";
            deleteButton.className = "btn btn-danger";
            deleteButton.onclick = function() { deleteAction(actions[i][0]); };
            button.appendChild(deleteButton);
            tableRow.appendChild(button);

            editTable.appendChild(tableRow);
        }
    }

    function fillEditAddModal() {
        $("#edit-body tr").remove();
        $("#edit-body div").remove();
        $(".modal-footer div").remove();
        $(".modal-footer").append(`
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            <button type="button" class="btn btn-primary" onclick="fillEditModal()">< Back</button>
                        </div>
                        <div class="col-3">
                            <input type="button" id="AddAction" value="Add Action" class="btn btn-success" onclick="addAction()"/>
                        </div>
                    </div>
                </div>`);
        $("#edit-body").append(`
                <div>
                    <input class="form-control" list="clusters_list" placeholder="-- Select a Cluster --" id="action_cluster" required>
                    <datalist id="clusters_list" required></datalist>
                    <label for="OffsetInput">Action time</label>
                    <input class="form-control" id="OffsetInput" type="time" required>
                    <label for="Activate">Activation</label>
                    <select class="form-control" id="Activate" required>
                        <option value="1">Turn ON</option>
                        <option value="0">Turn OFF</option>
                    </select>
                </div>`);
        makeRequest("GET", "Create_Helper.php?item=Clusters", clusterCallback);

    }

    function addAction() {
        let clusterName = $("#action_cluster").val();
        let time = $("#OffsetInput").val();
        let activation = ($("#Activate").val() === "0") ? 0 : 1;
        console.log(activation);
        console.log(time);
        let actionObj = {
            "ClusterName": clusterName,
            "Time": time,
            "Activation": activation,
            "EventID": event_id,
            "StartTime": startTime
        }

        let jsonStr = JSON.stringify(actionObj);
        $.ajax({
            url: "Create_Helper.php",
            type: "post",
            data: {"action": jsonStr},
            success: function(result) { alert("Added action dfsdfsfd"); location.reload();}
        });
        // makeRequest("GET", "Event_Helper.php?event_id=" + event_id + "&clustername=" + clusterName + "&timeoffset=" + time + "&activation=" + activation, function(result) { alert("Added action"); location.reload();});
    }

    function deleteAction(actionId) {
        console.log(actionId);
        makeRequest("GET", "Event_Helper.php?action_id=" + actionId, function(result) { alert("Deleted action " + actionId); location.reload(); });
    }

    /**
     * Function to add the clusters to the cluster dropdown in actions form.
     * @param responseText response from the server.
     */
    function clusterCallback(responseText) {
        console.log(responseText);
        let selectElement = document.getElementById('clusters_list');
        // NEED TO CATCH ERROR IF PARSE FAILS
        let clusters = JSON.parse(responseText);
        for (let i = 0; i < clusters.length; i++) {
            let option = document.createElement('option');
            console.log(clusters[i][1]);
            option.value = clusters[i][1];
            option.innerHTML = clusters[i][1];
            selectElement.append(option);
        }
    }

</script>
</body>
</html>

