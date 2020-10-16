<?php
/**
 * A file for handling POST and GET requests associated with the login page.
 */
require_once("../config/config.php");

// Creates a connection to the database using variables from the config file
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
 */
function checkUsernameAndPassword($conn, $username, $password) {
    $query = "CALL user_password(?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows == 0) {
        echo "Incorrect";
    } else {
        $result = $result->fetch_array(MYSQLI_ASSOC);
        $hashed_password = $result['hashed_password'];
        $user_id = $result['user_id'];
        if (password_verify($password, $hashed_password)) {
            $token = addToken($conn, $user_id);
            setcookie('loggedin', 'yes', time() + 60 * 20); // Sets cookie timeout to 20 minutes
            echo $token;
        } else {
            echo "Incorrect";
        }
    }
}

/**
 * Adds a token to the database for a current session and returns the token
 * @param  mysqli $conn connection to database
 * @param int $user_id user's ID
 * @return string the token for the user
 */
function addToken($conn, $user_id) {
    $token = generateRandomString();
    $query = "CALL add_token(?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $user_id, $token);
    $stmt->execute();
    $result = $stmt->get_result();
    return $token;
}

/**
 * Generates random string of specified length to be used as the token
 * @param int $length length of string to be generated
 * @return string token
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Sanitizes a given string and returns it.
 * @param string $var string to sanitize
 * @return string sanitized string
 */
function sanitizeString($var) {
    if (get_magic_quotes_gpc())
        $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}

/**
 * Echos an mysql error.
 *
 * @param string $error The error passed.
 */
function fatalError($error)
{
    $message = mysql_error();
    echo <<< _END
Something went wrong :/
<p>$error: $message</p>
_END;
}

// Closes database connection
$conn->close();
?>