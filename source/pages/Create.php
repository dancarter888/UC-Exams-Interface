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

        <h1> Create Test Step 1 </h1>

        <br > <br > <br >

        <form name="Details" action="Create2.php" method="post">
            Test Date <input type="date" name="test_date" /> <br />
            Test Name <input type="text" name="test_name" required /> <br />
            Room
            <div id="room_select"></div>
            <input list="rooms" id="test_room" name="test_room">
            <datalist id="rooms" required>
                <option value="" disabled selected> -- Select a room -- </option>
            </datalist> <button onclick="addRoom()">Add</button><br />
            Start Time <input type="time" name="test_stime" required /> <br />
            End Time <input type="time" name="test_etime" required /> <br />

            <input type="submit" value="Next" />
        </form>

        <h4 id="info"></h4>

        <script>
            let ROOMS = [];
            let roomsAdded = 0;

            // Make a get request to the URL
            makeRequest("GET", "Create_Helper.php?item=Rooms", roomCallback);

            /**
             * Function to add the rooms in response text to the datalist in the webpage.
             **/
            function roomCallback(responseText) {
                let selectElement = document.getElementById('rooms');
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
                    // Create a new input tag for the selected room
                    let item = document.createElement("input");
                    item.name = `room${roomsAdded++}`;
                    item.value = input.value;
                    item.readOnly = true;
                    roomDiv.appendChild(item);
                    roomDiv.appendChild(document.createElement("br"));

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
        </script>



    </body>
</html>