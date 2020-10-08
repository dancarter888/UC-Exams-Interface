<?php
require_once("../config/config.php");

// Creates a connection to the databse using variables form the config file
$conn = new mysqli($hostname, $username, $password, $database);

// Catches any error connecting to the database
if ($conn->connect_error)
{
    fatalError($conn->connect_error);
    return;
}

// Sanitizes the username and password given.
$username = $_POST["username"];
$password = $_POST["password"];
$username = sanitizeString($username);

// Creates a hash for the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Checks username and password are correct
checkUsernameAndPassword($conn, $username, $password);

/**
 * Checks the given username and hashed password match an entry in the authentication table
 * @param mysqli $conn A connection to a mysql database
 * @param string $username Username to check
 * @param string $password Inputted password
 * @return bool
 */
function checkUsernameAndPassword($conn, $username, $password) {
    $query = "CALL user_password(?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo "Incorrect username";
        return False;
    } else {
        $hashed_password = $result->fetch_array(MYSQLI_ASSOC)['hashed_password'];
        if (password_verify($password, $hashed_password)) {
            echo "Success";
            setcookie('loggedin', 'yes', time() + 60 * 20); // Sets cookie timeout to 20 minutes
        } else {
            echo "Incorrect password";
        }
    }
}

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

// Closes database connection
$conn->close();
?>