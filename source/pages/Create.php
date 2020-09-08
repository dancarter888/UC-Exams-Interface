<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/login.css">
    <title>Create</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body {
            font-family: "Lato", sans-serif;
        }

        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidebar a {
            padding: 20px 10px 10px 32px;
            text-decoration: none;
            font-size: 20px;
            color: grey;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            color: white;
        }


        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        .openbtn {
            font-size: 20px;
            cursor: pointer;
            background-color: #111;
            color: white;
            padding: 10px 15px;
            border: none;
        }



        .openbtn:hover {
            background-color: #444;
        }

        #main {
            transition: margin-left .5s;
            padding: 5px;
        }

        /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
        @media screen and (max-height: 450px) {
            .sidebar {padding-top: 15px;}
            .sidebar a {font-size: 18px;}
        }
    </style>

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

<script>
    function openNav() {
        document.getElementById("mySidebar").style.width = "175px";
        document.getElementById("main").style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("mySidebar").style.width = "0";
        document.getElementById("main").style.marginLeft= "0";
    }

</script>

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
                input.value = "";
            }
        }
    </script>



</body>
</html>