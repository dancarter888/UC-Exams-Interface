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
        <link rel="stylesheet" href="../css/login.css">
        <link rel="stylesheet" href="../css/NavBar.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- JavaScript -->
        <script src="../js/AJAX.js"></script>
        <script src="../js/NavBar.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <title>Create</title>
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

        <div id="F1">
            <h1> Create Test Step 1 </h1>

            <br > <br > <br >

            <form id="DetailsForm">
                Test Date <input type="date" name="test_date" id="test_date" /> <br />
                Test Name <input type="text" name="test_name" id="test_name" required /> <br />
                Room
                <ul id="room_select"></ul>
                <input list="rooms" id="test_room" name="test_room">
                <datalist id="rooms" required>
                    <option value="" disabled selected> -- Select A Room -- </option>
                </datalist>
                <button onclick="addRoom()">+ Add</button><br />
                Start Time <input type="time" name="test_stime" id="test_stime" required /> <br />
                End Time <input type="time" name="test_etime" id="test_etime" required /> <br />

                <input type="submit" value="Next" />
            </form>
        </div>

        <div id="F2">
            <h1> Create Test Step 2 </h1>

            <br > <br > <br >

            <form id="TypeForm">
                <table id="clusters">
                    <tr>
                        <th> Select </th>
                        <th> Type </th>
                        <th> Description </th>
                    </tr>
                </table>

                <input type="button" onclick="prevStep()" value="Prev" />
                <input type="submit" value="Next" />
            </form>
        </div>

        <div id="F3">
            <h1> Add Actions </h1>

            <br > <br > <br >

            <form id="ActionsForm">
                <ul id="ActionsList"></ul>

                Action time:    <input id="OffsetInput" type="time"> <br /><br />

                Activation:
                <select id="Activate">
                    <option value="1">Turn on</option>
                    <option value="0">Turn off</option>
                </select> <br />

                <button id="AddAction" onclick="addAction()">Add Action</button>

                <input type="button" onclick="prevStep()" value="Prev" />
                <input type="submit" value="Next" />
            </form>



        </div>

        <div id="F4">
            <h1> Create Test Review </h1>

            <br > <br > <br >

            <form id="ReviewForm">
                <h4 id="r_date">Test Date:</h4>  <br />
                <h4 id="r_name">Test Name:</h4>  <br />
                <h4 id="r_rooms">Test Rooms:</h4>  <br />
                <h4 id="r_stime">Test Start Time:</h4>  <br />
                <h4 id="r_etime">Test End Time:</h4>  <br />
                <h4 id="r_type">Test Type:</h4>  <br />
                <input type="button" onclick="prevStep()" value="Prev" />
                <input type="submit" value="Next" />
            </form>
        </div>

        <script>
            const ON = "block";
            const OFF = "none";

            let ROOMS = [];
            let roomsAdded = 0;
            let roomsSelected = [];

            let ACTIONS = [];

            let currentForm = 0;
            let formIds = ["F1", "F2", "F3", "F4"];

            // JSON object for storing the event
            let eventObj = {Date: "", Name:"", Rooms:[], StartTime: "", EndTime: "", Duration: "", TestType: ""};

            // Make a get request to the URL
            makeRequest("GET", "Create_Helper.php?item=Rooms", roomCallback);
            makeRequest("GET", "Create_Helper.php?item=Clusters", clusterCallback);
            setForms();

            $('#DetailsForm').submit(function () {
                eventObj["Date"] = $("#test_date").val();
                console.log($("#test_name").val());
                eventObj["Name"] = $("#test_name").val();
                eventObj["Rooms"] = roomsSelected;
                eventObj["StartTime"] = $("#test_stime").val();
                eventObj["EndTime"] = $("#test_etime").val();
                currentForm++;
                setForms();
                return false;
            });

            $('#TypeForm').submit(function () {
                eventObj["TestType"] = $("#test_type").val();
                currentForm++;
                setForms();
                return false;
            });

            $('#ActionsForm').submit(function () {
                //STUFF
                currentForm++;
                setForms();
                return false;
            });

            $('#ReviewForm').submit(function () {
                createEvent();
                currentForm++;
                setForms();
                return false;
            });

/*            /!**
             * Function that takes the information from the form and stores it in eventObj
             *!/
            function nextStep() {
                console.log("Working");
                if (currentForm == 0) {
                    eventObj["Date"] = $("#test_date").val();
                    console.log($("#test_name").val());
                    eventObj["Name"] = $("#test_name").val();
                    eventObj["Rooms"] = roomsSelected;
                    eventObj["StartTime"] = $("#test_stime").val();
                    eventObj["EndTime"] = $("#test_etime").val();
                } else if (currentForm == 1) {
                    eventObj["TestType"] = $("#test_type").val();
                } else if (currentForm == 3) {
                    createEvent();
                    return;
                }
                console.log(eventObj);

                currentForm++;
                setForms();
            }*/

            /**
             * Function for the back button to go back a step
             */
            function prevStep() {
                if (currentForm == 1) {
                    eventObj["TestType"] = $("#test_type").val();
                }
                console.log(eventObj);

                currentForm--;
                setForms();
            }


            /**
             * Toggle between the forms.
             */
            function setForms() {
                for (let i=0; i<formIds.length; i++) {
                    let ele = document.getElementById(formIds[i]);
                    for (let x of ele.children) {
                        x.style.display = (i == currentForm) ? ON : OFF;
                    }
                }
                if (currentForm == 3) {
                    updateFinalForm();
                }
            }

            /**
             * Updates the final form with the data the user has inputted.
             */
            function updateFinalForm() {
                $("#r_date").append("\t" + eventObj["Date"]);
                $("#r_name").append("\t" + eventObj["Name"]);
                $("#r_rooms").append("\t" + eventObj["Rooms"]);
                $("#r_stime").append("\t" + eventObj["StartTime"]);
                $("#r_etime").append("\t" + eventObj["EndTime"]);
                $("#r_type").append("\t" + eventObj["TestType"]);

            }

            function createEvent() {
                let jsonStr = JSON.stringify(eventObj);
                // $.ajax({
                //     url: "Create_Helper.php",
                //     type: "post",
                //     data: {user: jsonStr},
                //     success: created
                // });
                created("Test");
            }

            function created(responseText) {
                console.log(responseText);

                // Get these from responseText
                let eventID = 48;
                let clusterID = 8;
                for (let action of ACTIONS) {
                    action["EventID"] = eventID;
                    action["ClusterID"] = clusterID;
                    action["StartTime"] = eventObj["StartTime"]
                    jsonStr = JSON.stringify(action);
                    $.ajax({
                        url: "Create_Helper.php",
                        type: "post",
                        data: {"action": jsonStr},
                        success: final
                    });
                }

            }

            function final(responseText) {
                console.log(responseText);
                // Goto event page
            }

            /**
             * Function to add the rooms in response text to the datalist in the webpage.
             **/
            function roomCallback(responseText) {
                let selectElement = document.getElementById('rooms');
                console.log(selectElement.style.display);
                console.log(responseText);
                let rooms = JSON.parse(responseText);
                ROOMS = rooms;
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
                let roomDiv = document.getElementById("room_select");
                let input = document.getElementById("test_room");
                if (ROOMS.includes(input.value)) {
                    $("#room_select").append("<li>" + input.value + "</li>");
                    roomsSelected.push(input.value);

                    // Remove option for this room so it can't be selected more than once
                    let dList = document.getElementById("rooms");
                    for (let i=0; i<dList.children.length; i++) {
                        if (dList.children[i].value === input.value) {
                            dList.children[i].remove();
                            break;
                        }
                    }
                    ROOMS.splice(ROOMS.indexOf(input.value), 1);
                    input.value = "";
                }
            }

            function addAction() {
                let time = $("#OffsetInput").val();
                console.log(time);

                let activation = ($("#Activate").val() == "0") ? 0 : 1;
                console.log(activation);

                if (ACTIONS.length == 0) {
                    $("#ActionsForm").prepend("<h2> Actions: </h2>");
                }
                $("#ActionsList").append("<li> Time: " + time + " Activation: " + activation);
                let actionsObj = {
                    "Time": time,
                    "Activation": activation
                }
                ACTIONS.push(actionsObj);
            }

            /**
             * Function to add the clusters to the cluster selection form
             * @param responseText responsew from the server
             */
            function clusterCallback(responseText) {
                let table = document.getElementById('clusters');
                console.log(responseText);
                // NEED TO CATCH ERROR IF PARSE FAILS
                let clusters = JSON.parse(responseText);
                for (let i = 0; i < clusters.length; i++) {
                    let row = table.insertRow(i + 1);
                    let idCell = row.insertCell(0);
                    let nameCell = row.insertCell(1);
                    let descripCell = row.insertCell(2);

                    let radioBut = document.createElement('input');
                    radioBut.type = "radio";
                    radioBut.name = "test_type";
                    radioBut.id = "test_type";
                    radioBut.value = clusters[i][1];
                    radioBut.required = true;
                    idCell.appendChild(radioBut);

                    nameCell.innerHTML = clusters[i][1];
                    descripCell.innerHTML = clusters[i][2];
                }
            }
        </script>



    </body>
</html>