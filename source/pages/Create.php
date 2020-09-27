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
            <br> <br> <br>
            <a href="login.php">Logout</a>
        </div>

        <div id="main">
            <button class="openbtn" onclick="openNav()"> ☰ </button>
        </div>

        <div id="F1">
            <h1> Create Test Step 1 </h1>

            <br > <br > <br >

            <form name="Details">
                Test Date <input type="date" name="test_date" id="test_date" /> <br />
                Test Name <input type="text" name="test_name" id="test_name" required /> <br />
                Room
                <ul id="room_select"></ul>
                <input list="rooms" id="test_room" name="test_room">
                <datalist id="rooms" required>
                    <option value="" disabled selected> -- Select a room -- </option>
                </datalist> <button onclick="addRoom()">Add</button><br />
                Start Time <input type="time" name="test_stime" id="test_stime" required /> <br />
                End Time <input type="time" name="test_etime" id="test_etime" required /> <br />

                <input type="button" onclick="nextStep()" value="Next" />
            </form>
        </div>

        <div id="F2">
            <h1> Create Test Step 2 </h1>

            <br > <br > <br >

            <form name="Type">
                <table id="clusters">
                    <tr>
                        <th> Select </th>
                        <th> Type </th>
                        <th> Description </th>
                    </tr>
                </table>

                <input type="button" onclick="nextStep()" value="Next" />
            </form>
        </div>

        <div id="F3">
            <h1> Create Test Review </h1>

            <br > <br > <br >

            <form name="Type">
                <h4 id="r_date">Test Date:</h4>  <br />
                <h4 id="r_name">Test Name:</h4>  <br />
                <h4 id="r_rooms">Test Rooms:</h4>  <br />
                <h4 id="r_stime">Test Start Time:</h4>  <br />
                <h4 id="r_etime">Test End Time:</h4>  <br />
                <h4 id="r_type">Test Type:</h4>  <br />
                <input type="button" onclick="createEvent()" value="Next" />
            </form>
        </div>

        <!--<input type="button" onclick="createEvent()">Create</input>-->

        <script>
            const ON = "block";
            const OFF = "none";

            let ROOMS = [];
            let roomsAdded = 0
            let roomsSelected = [];

            let currentForm = 0
            let formIds = ["F1", "F2", "F3"]

            let eventObj = {Date: "", Name:"", Rooms:[], StartTime: "", EndTime: "", Duration: "", TestType: ""};

            // Make a get request to the URL
            makeRequest("GET", "Create_Helper.php?item=Rooms", roomCallback);
            makeRequest("GET", "Create_Helper.php?item=Clusters", clusterCallback);
            setForms();

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
                }
                console.log(eventObj);

                currentForm++;
                setForms();
            }

            function setForms() {
                for (let i=0; i<formIds.length; i++) {
                    let ele = document.getElementById(formIds[i]);
                    for (let x of ele.children) {
                        x.style.display = (i == currentForm) ? ON : OFF;
                    }
                }
                if (currentForm == 2) {
                    updateFinalForm();
                }
            }

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
                $.ajax({
                    url: "Create_Helper.php",
                    type: "post",
                    data: {user: jsonStr},
                    success: created
                });
            }

            function created(responseText) {
                console.log(responseText);
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
                    idCell.appendChild(radioBut);

                    nameCell.innerHTML = clusters[i][1];
                    descripCell.innerHTML = clusters[i][2];
                }
            }
        </script>



    </body>
</html>