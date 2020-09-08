<?php

session_start();

$_SESSION['test_date'] = $_POST['test_date'];
$_SESSION['test_name'] = $_POST['test_name'];
$_SESSION['test_room'] = $_POST['test_room'];
$_SESSION['test_stime'] = $_POST['test_stime'];
$_SESSION['test_etime'] = $_POST['test_etime'];

echo $_SESSION['test_date'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../css/login.css">
        <meta charset="UTF-8">
        <title>Create</title>

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
            document.getElementById("main").style.marginLeft = "200px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft= "0";
        }
    </script>
        <h1> Create Test Step 2 </h1>

        <br > <br > <br >

        <form name="Type" action="Create3.php" method="POST">
            <table id="clusters">
                <tr>
                    <th> Select </th>
                    <th> Type </th>
                    <th> Description </th>
                </tr>
            </table>

            <input type="submit" value="Next" />
        </form>

        <script>
            nocache = "&nocache=1";
            request = new asyncRequest()
            request.open("GET", "Create_Helper.php?item=Clusters" + nocache, true)
            request.onreadystatechange = function()
            {
                if (this.readyState == 4)
                {
                    if (this.status == 200)
                    {
                        if (this.responseText != null)
                        {
                            let table = document.getElementById('clusters');
                            console.log(this.responseText);
                            // NEED TO CATCH ERROR IF PARSE FAILS
                            let clusters = JSON.parse(this.responseText);
                            for (let i=0; i<clusters.length; i++) {
                                let row = table.insertRow(i+1);
                                let idCell = row.insertCell(0);
                                let nameCell = row.insertCell(1);
                                let descripCell = row.insertCell(2);

                                let radioBut = document.createElement('input');
                                radioBut.type = "radio";
                                radioBut.name = "test_type";
                                radioBut.value = clusters[i][0];
                                idCell.appendChild(radioBut);

                                nameCell.innerHTML = clusters[i][1];
                                descripCell.innerHTML = clusters[i][2];
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
        </script>
    </body>
</html>