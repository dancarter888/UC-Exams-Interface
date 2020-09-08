<?php
    if (isset($_POST['test_date'])) {
        session_start();
        $_SESSION['test_date'] = $_POST['test_date'];
        $_SESSION['test_name'] = $_POST['test_name'];
        $_SESSION['test_stime'] = $_POST['test_stime'];
        $_SESSION['test_etime'] = $_POST['test_etime'];

        $rooms = array();
        $count = 0;
        while (isset($_POST['room' . $count])) {
            array_push($rooms, $_POST['room' . $count++]);
        }
        $_SESSION['test_rooms'] = $rooms;
    } else {
        header("Location: http://localhost/info263-project/source/pages/Create.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- CSS -->
        <link rel="stylesheet" href="../css/login.css">
        <link rel="stylesheet" href="../css/NavBar.css">

        <!-- JavaScript -->
        <script src="../js/AJAX.js"></script>
        <script src="../js/NavBar.js"></script>

        <meta charset="UTF-8">
        <title>Create</title>
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

            <input type="submit" value="Next" required />
        </form>

        <script>
            makeRequest("GET", "Create_Helper.php?item=Clusters", clusterCallback);

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
                    radioBut.value = clusters[i][1];
                    idCell.appendChild(radioBut);

                    nameCell.innerHTML = clusters[i][1];
                    descripCell.innerHTML = clusters[i][2];
                }
            }
        </script>
    </body>
</html>