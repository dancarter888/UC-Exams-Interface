<?php
require_once("../config/config.php");

$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error)
{
    fatalError($conn->connect_error);
    return;
}

//Not entirely sure if we need to check they exist as you can only submit when username and password has been filled.
if(isset($_POST["username"])) {
    $username = $_POST["username"];
    $username = sanitizeString($username);
}
if (isset($_POST["password"])) {
    $password = $_POST["password"];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
}

if(isset($_POST['add']))
{
    $user = createUser($_POST);

    if (!is_null($user))
    {
        addUser($conn, $user);
    }
}

if(isset($_POST['remove']))
{
    removeUser($conn, $_POST['remove']);
}

function createUser($user) {
    if ($user['new_username'] !== '' && $user['new_username'] !== NULL && $user['new_password'] !== '' && $user['new_password'] !== NULL) {
        return array($user['new_username'], $user['new_password']);
    }
    return NULL;
}

/**
 * Just to test out the logins
 * @param $conn
 * @param $user
 */
function addUser($conn, $user) {
    $username = $user[0];
    $password = $user[1];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("CALL add_user(?, ?);");
    $stmt->bind_param('ss', $username, $hashed_password);
    $stmt->execute();

    if ($stmt->errno) {
        fatalError($stmt->error);
    } else {
        echo "User " . $username . " added!";
    }
}

function removeUser($conn, $user_id) {
    $stmt = $conn->prepare("CALL remove_user(?);");
    $stmt->bind_param('s', $user_id);
    $stmt->execute();

    if ($stmt->errno) {
        fatalError($stmt->error);
    } else {
        echo "User removed!";
    }
}

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
        //echo $password, $hashed_password;
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

$conn->close();
?>