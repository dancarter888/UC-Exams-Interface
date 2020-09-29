<!--Code obtained from https://www.w3schools.com/howto/howto_css_login_form.asp-->
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/login.css">
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <form action="login.php" method="post">
        <div class="container">
            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" id="username" required>

            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" id="password" required>

            <button type="submit">Login</button>
            <label>
                <input type="checkbox" checked="checked" name="remember"> Remember me
            </label>
        </div>

        <div class="container" style="background-color:#f1f1f1">
            <span class="psw">Forgot <a href="#">password?</a></span>
        </div>
    </form>

    <form action="login.php" method="post" enctype='multipart/form-data'>
        <div class="container">
            <b>TEST</b> Add user<br/>
            <input type='hidden' name='add' value='1' />
            <label>
                Username:
                <input type='text' placeholder='Enter Username' name='new_username' size='15' required/>
            </label>
            <label>
                Password:
                <input type='password' placeholder='Enter Password' name='new_password' size='15' required/>
            </label>
            <button type="submit">Add User</button>
        </div>
    </form>

    <form method="post" action="login.php" enctype="multipart/form-data">
        <div class="container">
        <label>
            <b>TEST</b> Remove user by id
            <input type="text" placeholder='Enter User ID' name="remove" size=10/>
        </label>
            <button type="submit">Remove user by id</button>
        </div>
    </form>
</body>
</html>

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
    $result = $conn->query("CALL user_password('{$username}')");
    if ($result->num_rows == 0) {
        echo "Incorrect username";
        return False;
    } else {
        $hashed_password = $result->fetch_array(MYSQLI_ASSOC)['hashed_password'];
        if (password_verify($password, $hashed_password)) {
            echo "Success";
            setcookie('loggedin', 'yes', ['samesite' => 'Lax'], time() + 60 * 5); // Sets cookie timeout to 5 minutes
            header('Location: Events.php');
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