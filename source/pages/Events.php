<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events</title>
</head>
<body>

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