<?php
// Deletes cookie if it is set
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
        function requestLogin() {
            let username = $('#username').val()
            let password = $('#password').val()

            $.ajax({
                url: "login_Helper.php",
                type: "post",
                data: {username: username,
                       password: password},
                success: checkLogin
            });
        }

        function checkLogin(responseText) {
            if (responseText === "Success") {
                console.log(responseText);
                document.location.href = "Events.php";
            } else {
                console.log(responseText);
                $('#formMessage').text("Invalid Login");
            }
        }

        $('#login-form').submit(function () {
            requestLogin();
            return false;
        });
    </script>
</html>