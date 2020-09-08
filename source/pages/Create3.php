<?php
    session_start();
    $_SESSION['test_type'] = $_POST['test_type'];
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
        <h1> Create Test Review </h1>

        <br > <br > <br >

        <form name="Type" action="Create.php" method="POST">
            <h4>Test Date:</h4> <?php echo $_SESSION['test_date'] ?> <br />
            <h4>Test Name:</h4> <?php echo $_SESSION['test_name'] ?> <br />
            <h4>Test Room:</h4> <?php echo $_SESSION['test_room'] ?> <br />
            <h4>Test Start Time:</h4> <?php echo $_SESSION['test_stime'] ?> <br />
            <h4>Test End Time:</h4> <?php echo $_SESSION['test_etime'] ?> <br />
            <h4>Test Duration:</h4> <?php echo (strtotime($_SESSION['test_etime']) - strtotime($_SESSION['test_stime'])) / 3600; ?> hours
            <h4>Test Type:</h4> <?php echo $_SESSION['test_type'] ?> <br />
            <input type="submit" />
        </form>

    </body>
</html>
