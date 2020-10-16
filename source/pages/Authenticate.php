<?php
require_once("../config/config.php");

// Creates a connection to the database using variables from the config file
$conn = new mysqli($hostname, $username, $password, $database);

// Catches any error connecting to the database
if ($conn->connect_error)
{
    fatalError($conn->connect_error);
    return;
}

//Checks if there is a get request for the users token
if (isset($_GET['token']))
{
    $token = $_GET['token'];
    $query = "CALL check_token(?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    //Echos True if the user token exists in the DB, False otherwise
    if ($result->num_rows == 0) {
        echo 'False';
    } else {
        echo 'True';
    }
} else {
    echo 'False';
}

// Closes database connection
$conn->close();