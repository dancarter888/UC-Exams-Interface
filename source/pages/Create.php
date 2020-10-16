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
        <link rel="stylesheet" href="../css/Create.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!-- JavaScript -->
        <script src="../js/AJAX.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <title>Create</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8">
    </head>
    <body>
        <!-- Navigation bar -->
        <nav class="navbar navbar-expand navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="Create.php">Create Event </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Events.php">Events <span class="sr-only">(current)</span></a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <a class="btn btn-outline-danger" href="Login.php" role="button">Logout</a>
                </form>
            </div>
        </nav>

        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-auto">
                    <h1> Create Event </h1>

                    <!-- progressbar -->
                    <ul id="progressbar">
                        <li id="details-progress">Details</li>
                        <li id="actions-progress">Actions</li>
                        <li id="review-progress">Review</li>
                    </ul>
                    <div class="progress">
                        <div id="progressanimate" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
                    </div>

                    <br /><br />

                    <!-- Details form -->
                    <div id="F1">
                        <div class="container">
                            <h2> Event Details </h2>
                        </div>

                        <br>

                        <form id="DetailsForm">
                            <div class="form-group">
                                <label for="test_date">Event Date</label>
                                <input class="form-control" type="date" name="test_date" id="test_date" required/>
                            </div>

                            <br/>

                            <div class="form-group">
                                <label for="test_name">Event Name</label>
                                <input class="form-control" type="text" placeholder="Enter Name" name="test_name"
                                       id="test_name" required/>
                            </div>

                            <br/>

                            <div class="row align-items-end">
                                <div class="col-8">
                                    <label for="test_room">Room</label>
                                    <ul id="room_select">
                                    </ul>
                                    <input class="form-control" list="rooms" placeholder="-- Select Rooms --"
                                           id="test_room" name="test_room">
                                    <datalist id="rooms" required>
                                        <option value="" disabled selected> -- Select A Room --</option>
                                    </datalist>
                                </div>
                                <div class="col-4">
                                    <input type="button" onclick="addRoom()" value="+ Add" class="btn btn-secondary"/>
                                </div>
                            </div>

                            <br/>
                            <br/>

                            <div class="form-group">
                                <label for="test_stime">Start Time</label>
                                <input class="form-control" type="time" name="test_stime" id="test_stime" required/>
                            </div>

                            <br/>

                            <div class="row align-items-end row justify-content-end">
                                <div class="col"></div>
                                <div class="col">
                                    <input type="submit" value="Add Actions >" class="btn btn-primary"/>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Actions form -->
                    <div id="F2">
                        <div class="container">
                            <h2> Event Actions </h2>

                            <form id="ActionsForm">

                                <div class="form-group">
                                    <ul id="ActionsList"></ul>

                                    <label for="clusters_list">Cluster</label>
                                    <input class="form-control" list="clusters_list"
                                           placeholder="-- Select a Cluster --" id="action_cluster" required>
                                    <!--name="test_room">-->
                                    <datalist id="clusters_list" required>
                                    </datalist>
                                </div>

                                <div class="form-group">
                                    <label for="OffsetInput">Action time</label>
                                    <input class="form-control" id="OffsetInput" type="time" required>
                                </div>

                                <div class="form-group">
                                    <label for="Activate">Activation</label>
                                    <select class="form-control" id="Activate" required>
                                        <option value="1">Turn ON</option>
                                        <option value="0">Turn OFF</option>
                                    </select>
                                </div>

                                <input type="submit" id="AddAction" value="+ Add Action" class="btn btn-secondary"/>
                                </br></br>
                                <input type="button" onclick="prevStep()" value="< Event Details"
                                       class="btn btn-primary"/>
                                <input type="button" onclick="reviewForm()" value="Review Event >"
                                       class="btn btn-primary"/>
                            </form>
                        </div>
                    </div>

                    <!-- Event review form -->
                    <div id="F3">
                        <div class="container">
                            <h2> Event Review </h2>

                            <br> <br>

                            <form id="ReviewForm">
                                <h4>Test Name: </h4>
                                <div id="r_name"></div>
                                <br/>
                                <h4>Test Rooms: </h4>
                                <div id="r_rooms"></div>
                                <br/>
                                <h4>Test Date: </h4>
                                <div id="r_date"></div>
                                <br/>
                                <h4>Test Start Time: </h4>
                                <div id="r_stime"></div>
                                <br/><br/>

                                <h3 class="text-center">Actions</h3>
                                <ul id="ActionsReviewList">
                                </ul>
                                <br/> <br/>

                                <input type="button" onclick="prevStep()" value="< Add Actions"
                                       class="btn btn-primary"/>
                                <input type="submit" value="Create Event" class="btn btn-success"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Checks the user has the has the correct token
            makeRequest("GET", "Authenticate.php?token=" + window.localStorage.getItem('token'), function(response) {
                if (response !== "True") {
                    window.location.href = window.location.href.replace("Create.php", "Login.php");
                }
            });

            // Constants disable/enable elements
            const ON = "block";
            const OFF = "none";

            // Variables to keep track of the rooms
            let ROOMS = [];
            let roomsAdded = 0;
            let roomsSelected = [];

            // A list to keep track of the actions
            let ACTIONS = [];

            // Variables to change the state of the page
            let currentForm = 0;
            let formIds = ["F1", "F2", "F3"];

            // JSON object for storing the event
            let eventObj = {Date: "", Name:"", Rooms:[], StartTime: ""};

            // Make a get request to the URL
            makeRequest("GET", "Create_Helper.php?item=Rooms", roomCallback);
            makeRequest("GET", "Create_Helper.php?item=Clusters", clusterCallback);
            setForms();

            /**
             * To remove a room
             * */
            $('body').on("click", ".remove-room", function() {
                let roomName = $(this).attr('id');
                removeRoom(roomName);
            });

            /**
             * To remove an action
             * */
            $('body').on("click", ".remove-action", function() {
                let action = $(this).attr('id');
                removeAction(action);
            });

            /**
             * Remove the given room from the selected rooms
             * @param roomName the name of the room to be removed
             */
            function removeRoom(roomName) {
                let roomIndex = roomsSelected.indexOf(roomName);
                if (roomIndex > -1) {
                    roomsSelected.splice(roomIndex, 1);
                }
                document.getElementById(roomName).remove();

                ROOMS.push(roomName);
                roomCallback(ROOMS.sort());
            }

            /**
             * Remove the given action from the added actions
             * @param action the action to be removed
             */
            function removeAction(action) {
                var actionArray = action.split(",");
                let clusterName = actionArray[0];
                let actionTime = actionArray[1];
                let actionActivation = parseInt(actionArray[2]);
                let i = 0;
                let found = false;
                let actionIndex = null;
                while (i < ACTIONS.length && !found) {
                    if (clusterName === ACTIONS[i]['ClusterName'] && actionTime === ACTIONS[i]['Time'] && actionActivation === ACTIONS[i]['Activation']) {
                        found = true;
                        actionIndex = i;
                    }
                    i++;
                }

                if (actionIndex > -1) {
                    ACTIONS.splice(actionIndex, 1);
                }

                document.getElementById(action).remove();
            }

            /**
             * Submit method for the 1st page (Details)
             */
            $('#DetailsForm').submit(function () {
                let rooms = document.getElementById("room_select");
                if (rooms.getElementsByTagName("li").length > 0) {
                    eventObj["Date"] = $("#test_date").val();
                    eventObj["Name"] = $("#test_name").val();
                    eventObj["Rooms"] = roomsSelected;
                    eventObj["StartTime"] = $("#test_stime").val();
                    currentForm++;
                    setForms();
                } else {
                    alert("You must add at least one room.");
                }
                return false;
            });

            /**
             * Submit method for the 2nd page (Type)
             */
            $('#TypeForm').submit(function () {
                currentForm++;
                setForms();
                return false;
            });

            /**
             * Submit method for the 3rd page (Actions)
             */
            $('#ActionsForm').submit(function () {
                addAction();
                return false;
            });

            /**
             * Submit method for the 4th page (Review)
             */
            $('#ReviewForm').submit(function () {
                createEvent();
                return false;
            });

            /**
             * Function for the back button to go back a step
             */
            function prevStep() {
                if (currentForm == 1) {
                    eventObj["TestType"] = $("#test_type").val();
                }

                currentForm--;
                setForms();
            }

            /**
             * Gets the actions given in the action list and checks if valid
             */
            function reviewForm() {
                let actions = document.getElementById("ActionsList");
                let actionsValid = checkActions();
                if (actions.getElementsByTagName("li").length > 0 && actionsValid) {
                    currentForm ++;
                    setForms();
                } else {
                    alert("Every activation of a Cluster must have a corresponding deactivation.");
                }
            }

            /**
             * Toggle between the different forms based on the value of currentForm.
             */
            function setForms() {
                for (let i=0; i<formIds.length; i++) {
                    let ele = document.getElementById(formIds[i]);
                    for (let x of ele.children) {
                        x.style.display = (i == currentForm) ? ON : OFF;
                    }
                }
                $("#progressanimate").css('width', `${((currentForm + 1) * 25)}%`);

                if (currentForm == 0) {
                    $('#details-progress').css("font-weight", "bold");
                    $('#actions-progress').css("font-weight", "normal");
                    $('#review-progress').css("font-weight", "normal");
                } else if (currentForm == 1) {
                    $('#details-progress').css("font-weight", "normal");
                    $('#actions-progress').css("font-weight", "bold");
                    $('#review-progress').css("font-weight", "normal");
                } else if (currentForm == 2) {
                    $('#details-progress').css("font-weight", "normal");
                    $('#actions-progress').css("font-weight", "normal");
                    $('#review-progress').css("font-weight", "bold");
                    updateFinalForm();
                }
            }

            /**
             * Updates the final form with the data the user has inputted.
             */
            function updateFinalForm() {
                let roomString = "";
                for (let room of eventObj["Rooms"]) {
                    roomString += `${room} <br />`;
                }

                $("#r_name").html(eventObj["Name"]);
                $("#r_date").html(eventObj["Date"]);
                $("#r_rooms").html(roomString);
                $("#r_stime").html(eventObj["StartTime"]);

                $("#ActionsReviewList").empty();
                for (let action of ACTIONS) {
                    let displayActivation = (action["Activation"] === 0) ? "OFF" : "ON";
                    $("#ActionsReviewList").append("<li>" + action["ClusterName"] + " - " + action["Time"] + " - " + displayActivation);
                }
            }

            /**
             * Convert the created event into a JSON string and asynchronously send a post request to create it.
             * */
            function createEvent() {
                let jsonStr = JSON.stringify(eventObj);
                $.ajax({
                    url: "Create_Helper.php",
                    type: "post",
                    data: {"event": jsonStr},
                    success: created
                });
            }

            /**
             * Called after the event has successfully been created, it asynchronously sends a post request for
             * each action to create it.
             *
             * @param responseText The id of the created event
             * */
            function created(responseText) {
                let eventID = responseText;
                for (let action of ACTIONS) {
                    action["EventID"] = eventID;
                    action["StartTime"] = eventObj["StartTime"];
                    let jsonStr = JSON.stringify(action);
                    $.ajax({
                        url: "Create_Helper.php",
                        type: "post",
                        data: {"action": jsonStr},
                        success: final
                    });
                }

            }

            /**
             * Called when the actions have been added, goes to the events page.
             * */
            function final(responseText) {
                document.location.href = "Events.php";
            }

            /**
             * Function to add the rooms in response text to the datalist in the webpage.
             *
             * @param responseText the rooms.
             **/
            function roomCallback(responseText) {
                let selectElement = document.getElementById('rooms');
                $('#rooms').empty();

                if (Array.isArray(responseText)){
                    var rooms = responseText;
                } else {
                    var rooms = JSON.parse(responseText);
                    ROOMS = rooms;
                }
                for (let i=0; i<rooms.length; i++) {
                    let option = document.createElement('option');
                    option.value = rooms[i];
                    option.innerHTML = rooms[i];
                    selectElement.appendChild(option);
                }
            }

            /**
             * If the input from datalist is valid then add it to the selected rooms.
             */
            function addRoom()
            {
                let input = document.getElementById("test_room");
                if (ROOMS.includes(input.value)) {
                    roomsSelected.push(input.value);
                    let closeBtn = document.createElement('button');
                    closeBtn.className = 'close remove-room';
                    closeBtn.id = input.value;
                    closeBtn.type = 'button';
                    closeBtn.innerHTML = '×'
                    let room = document.createElement('li');
                    room.innerHTML = input.value;
                    room.id = input.value;
                    room.appendChild(closeBtn);
                    $("#room_select").append(room);

                    ROOMS.splice(ROOMS.indexOf(input.value), 1);
                    input.value = "";

                    roomCallback(ROOMS);
                }
            }

            /**
             * Gets the input from the inputs associated with actions and creates an actions object which is saved.
             * */
            function addAction() {
                let clusterName = $("#action_cluster").val();
                let time = $("#OffsetInput").val();
                let activation = ($("#Activate").val() === "0") ? 0 : 1;
                let displayActivation = activation === 0 ? "OFF" : "ON";
                if (ACTIONS.length === 0) {
                    $("#ActionsForm").prepend("<h3> Actions: </h3>");
                }

                let actionsObj = {
                    "ClusterName": clusterName,
                    "Time": time,
                    "Activation": activation
                }

                if (checkAction(actionsObj)) {
                    let closeBtn = document.createElement('button');
                    closeBtn.className = 'close remove-action';
                    closeBtn.id = clusterName + ',' + time + ',' + activation;
                    closeBtn.type = 'button';
                    closeBtn.innerHTML = '×'

                    let action = document.createElement('li');
                    action.innerHTML = clusterName + " - " + time + " - " + displayActivation;
                    action.id = clusterName + ',' + time + ',' + activation;
                    action.appendChild(closeBtn);
                    $("#ActionsList").append(action);
                    ACTIONS.push(actionsObj);
                } else {
                    alert("Actions of the same Cluster must have different times.");
                }
            }

            /**
             * Function for checking that for every activation of a cluster there is a corresponding deactivation.
             * @return whether the actions are valid or not
             **/
            function checkAction(addedAction) {
                let validity = true;

                for (let action of ACTIONS) {
                    if (addedAction['ClusterName'] === action['ClusterName'] && addedAction['Time'] === action['Time']) {
                        validity = false;
                    }
                }

                return validity;
            }

            /**
             * Function for checking that for every activation of a cluster there is a corresponding deactivation.
             * @return whether the actions are valid or not
             **/
            function checkActions() {
                let validity = true;

                for (let action of ACTIONS) {
                    let clusterActivations = ACTIONS.filter((act) => {
                        return act["ClusterName"] === action["ClusterName"] && act["Activation"] === 1;
                    }).length;

                    let clusterDeactivations = ACTIONS.filter((act) => {
                        return act["ClusterName"] === action["ClusterName"] && act["Activation"] !== 1;
                    }).length;

                    if (clusterActivations !== clusterDeactivations) {
                        validity = false;
                    }
                }

                return validity;
            }

            /**
             * Function to add the clusters to the cluster dropdown in actions form.
             * @param responseText response from the server.
             */
            function clusterCallback(responseText) {
                let selectElement = document.getElementById('clusters_list');
                let clusters = JSON.parse(responseText);
                for (let i = 0; i < clusters.length; i++) {
                    let option = document.createElement('option');
                    option.value = clusters[i][1];
                    option.innerHTML = clusters[i][1];
                    selectElement.appendChild(option);
                }
            }
        </script>
    </body>
</html>