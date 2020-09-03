<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/login.css">
    <meta charset="UTF-8">
    <title>Create</title>
</head>
<body>
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

        nocache = "&nocache=1";
        request = new asyncRequest()
        request.open("GET", "Create_Helper.php?item=Rooms" + nocache, true)
        request.onreadystatechange = function()
        {
            if (this.readyState == 4)
            {
                if (this.status == 200)
                {
                    if (this.responseText != null)
                    {
                        let selectElement = document.getElementById('rooms');
                        console.log(this.responseText);
                        let rooms = JSON.parse(this.responseText);
                        ROOMS = rooms;
                        for (let i=0; i<rooms.length; i++) {
                            let option = document.createElement('option');
                            option.value = rooms[i];
                            option.innerHTML = rooms[i];
                            selectElement.appendChild(option);
                        }
                    }
                    else alert("Communication error: No data received")
                }
                else alert( "Communication error: " + this.statusText)
            }
        }
        request.send(null)
        function asyncRequest()
        {
            let request;
            try
            {
                request = new XMLHttpRequest();
            }
            catch(e1)
            {
                try
                {
                    request = new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch(e2)
                {
                    try
                    {
                        request = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    catch(e3)
                    {
                        request = false
                    }
                }
            }
            return request
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
            }
        }
    </script>
</body>
</html>