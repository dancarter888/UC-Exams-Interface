<?php
    if (isset($_POST['test_type'])) {
        session_start();
        $_SESSION['test_type'] = $_POST['test_type'];
    } else {
        header("Location: http://localhost/info263-project/source/pages/Create2.php");
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


        <h1> Create Test Review </h1>

        <br > <br > <br >

        <form name="Type" action="Create.php" method="POST">
            <h4>Test Date:</h4> <?php echo $_SESSION['test_date'] ?> <br />
            <h4>Test Name:</h4> <?php echo $_SESSION['test_name'] ?> <br />
            <h4>Test Rooms:</h4> <?php foreach ($_SESSION['test_rooms'] as $key => $val) { echo $val . '<br />'; } ?>
            <h4>Test Start Time:</h4> <?php echo $_SESSION['test_stime'] ?> <br />
            <h4>Test End Time:</h4> <?php echo $_SESSION['test_etime'] ?> <br />
            <h4>Test Duration:</h4> <?php echo (strtotime($_SESSION['test_etime']) - strtotime($_SESSION['test_stime'])) / 3600; ?> hours
            <h4>Test Type:</h4> <?php echo $_SESSION['test_type'] ?> <br />
            <input type="submit" />
        </form>
    </body>
</html>
