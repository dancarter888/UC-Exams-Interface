<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/login.css">
    <meta charset="UTF-8">
    <title>Create</title>
</head>
<body>
<h1> Create Test Step 1 </h1>

<br > <br > <br >

<form name="Details" action="Create2.php" method="post">
    Test Date <input type="date" name="test_date" /> <br />
    Test Name <input type="text" name="test_name" required /> <br />
    Room
    <select id="room" name="test_room">
        <option value="" disabled selected> -- Select a room -- </option>
    </select> <br />
    Time <input type="time" name="test_time" required /> <br />

    <input type="submit" value="Next" />
</form>

</body>
</html>