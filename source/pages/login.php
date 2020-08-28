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
}
if (isset($_POST["password"])) {
    $hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT);
}

checkUsernameAndPassword($conn, $username, $hashed_password);

/**
 * Checks the given username and hashed password match an entry in the authentication table
 *
 * @param mysqli $conn A connection to a mysql database
 * @param string $username Username to check
 * @param string $hashed_password Hashed password to check
 * @return bool True if the username and password match
 */
function checkUsernameAndPassword($conn, $username, $hashed_password) {
    $query = "SELECT password FROM authentication WHERE username = '{$username}'";
    $password = $conn->query($query);
    return password_verify($password, $hashed_password);
}