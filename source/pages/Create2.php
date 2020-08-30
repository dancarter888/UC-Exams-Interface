<?php

session_start();

$_SESSION['test_date'] = $_POST['test_date'];
$_SESSION['test_name'] = $_POST['test_name'];
$_SESSION['test_room'] = $_POST['test_room'];
$_SESSION['test_time'] = $_POST['test_time'];

echo $_SESSION['test_date'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../css/login.css">
        <meta charset="UTF-8">
        <title>Create</title>
    </head>
    <body>
        <h1> Create Test Step 2 </h1>

        <br > <br > <br >

        <form name="Type" action="Create3.php" method="POST">
            <table>
                <tr>
                    <th> Select </th>
                    <th> Type </th>
                    <th> Description </th>
                </tr>
                <tr>
                    <td> <input type="radio" name="test_type" value="type1" /> </td>
                    <td> 1st test type </td>
                    <td> 1st test type description </td>
                </tr>
                <tr>
                    <td> <input type="radio" name="test_type" value="type2" /> </td>
                    <td> 2nd test type </td>
                    <td> 2nd test type description </td>
                </tr>
                <tr>
                    <td> <input type="radio" name="test_type" value="type3" /> </td>
                    <td> 3rd test type </td>
                    <td> Add in php from db </td>
                </tr>
            </table>

            <input type="submit" value="Next" />
        </form>

    </body>
</html>