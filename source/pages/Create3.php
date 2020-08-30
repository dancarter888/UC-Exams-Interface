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
    </head>
    <body>
        <h1> Create Test Review </h1>

        <br > <br > <br >

        <form name="Type" action="Create.php" method="POST">
            <h4>Test Date:</h4> <?php echo $_SESSION['test_date'] ?> <br />
            <h4>Test Name:</h4> <?php echo $_SESSION['test_name'] ?> <br />
            <h4>Test Room:</h4> <?php echo $_SESSION['test_room'] ?> <br />
            <h4>Test Name:</h4> <?php echo $_SESSION['test_time'] ?> <br />
            <h4>Test Type:</h4> <?php echo $_SESSION['test_type'] ?> <br />
            <input type="submit" />
        </form>

    </body>
</html>
