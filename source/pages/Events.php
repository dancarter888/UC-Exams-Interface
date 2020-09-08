<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        document.getElementById("main").style.marginLeft = "200px";
    }

    function closeNav() {
        document.getElementById("mySidebar").style.width = "0";
        document.getElementById("main").style.marginLeft= "0";
    }
</script>

</body>
</html>

<?php
require_once("../config/config.php");

echo "hello world";

$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error)
{
    fatalError($conn->connect_error);
    return;
}

//Filters
/*if(isset($_POST["filter1"])) {
    $filter1 = $_POST["filter1"];
    $filter1 = sanitizeString($filter1);
}*/

/**
 * Sanitizes a given string and returns it.
 * @param $var
 * @return string
 */
function sanitizeString($var) {
    if (get_magic_quotes_gpc())
        $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}

$stmt = $conn->prepare("CALL tserver.get_front(?);");

$str = "00:27:0e:23:65:ac";
$stmt->bind_param('s', $str);

$stmt->execute();

if ($stmt->errno)

{

    fatalError($stmt->error);

}

$conn->close();
?>