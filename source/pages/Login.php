<?php
/*
Deletes cookie if it is set.
Used for logout, when logout is clicked on another page it
redirects to this page and then deletes the cookie.
*/
if (isset($_COOKIE['loggedin'])) {
    setcookie('loggedin', 'yes', time() - 3600); // Deletes cookie
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- CSS -->
        <link rel="stylesheet" href="../css/Login.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!-- JavaScript -->
        <script src="../js/AJAX.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
        <div class="wrapper fadeInDown">
            <div id="formContent">
                <br/> <br/>
                <form id="login-form">
                    <input type="text" id="username" name="username" class="fadeIn second" placeholder="username" value="username" required>
                    <input type="password" id="password" name="password" class="fadeIn third" placeholder="password" value="password" required>
                    <input type="submit" class="fadeIn fourth" value="Log In">
                </form>

                <!-- Footer -->
                <div id="formFooter">
                    <div id="formMessage">
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        // Removes any tokens if they exist
        if (localStorage.getItem('token') !== null) {
            localStorage.removeItem('token');
        }

        function requestLogin() {
            /**
             * Called when the login button is pressed.
             * Gets the username and password inputted by user, gives it to Login_Helper.php
             * and checks the response, if incorrect, displays an error message otherwise
             * redirects the user to the Events.php page
             */
            let username = $('#username').val()
            let password = $('#password').val()
            $.ajax({
                url: "Login_Helper.php",
                type: "post",
                data: {username: username,
                       password: password},
                success: function(responseText) {
                    if (responseText !== "Incorrect") {
                        window.localStorage.setItem('token', responseText);
                        document.location.href = "Events.php";
                    } else {
                        $('#formMessage').text("Invalid Login");
                    }
                }
            });
        }

        // Calls requestLogin when the loggin button pressed
        $('#login-form').submit(function () {
            requestLogin();
            return false;
        });
    </script>
</html>