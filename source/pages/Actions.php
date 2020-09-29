<!DOCTYPE html>
<html lang="en">
<head>
    <!-- CSS -->
    <link rel="stylesheet" href="../css/NavBar.css">
    <link rel="stylesheet" href="../css/pagination.css">

    <!-- JavaScript -->
    <script src="../js/AJAX.js"></script>
    <script src="../js/NavBar.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    <title>Create</title>
    <script src="../js/pagination.js"></script>

    <title>Actions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8">
</head>
<body>
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <a href="Create.php">Create Event</a>
        <a href="Events.php"> Events </a>
        <a href="Actions.php"> Actions </a>
        <br> <br> <br>
        <a href="login.php">Logout</a>
    </div>

    <div id="main">
        <button class="openbtn" onclick="openNav()"> ☰ </button>
    </div>

    <h1> Actions </h1>

    <br > <br > <br >

    <form>
        <input type="text" size="30" onkeyup="showResult(this.value)">
        <div id="livesearch"></div>
    </form>
    <table id="actions-table">
    </table>
    <nav id="pagination-container" class="pagination"></nav>

<script>
    let ACTIONS = [];

    // Make a get request to the URL
    makeRequest("GET", "Actions_Helper.php?count=All", pagination);

    function pagination(responseText) {
        let eventsTable = document.getElementById('actions-table');
        let parsedResponse = JSON.parse(responseText);
        let fieldNames = parsedResponse[0];
        let actions = parsedResponse[1];
        let headerRow = document.createElement('tr');
        for (let i=0; i< fieldNames.length; i++) {
            let tableHeader = document.createElement('th');
            tableHeader.innerHTML = fieldNames[i];
            headerRow.appendChild(tableHeader);
        }
        eventsTable.appendChild(headerRow);
        $('#pagination-container').pagination({
            dataSource: actions,
            pageSize: 20,
            callback: function(data, pagination) {
                structureDataTable(data);
                console.log(pagination);
            }
        })
    }

    function structureDataTable(data) {
        let actionsTable = document.getElementById('actions-table');
        let actions = document.getElementsByClassName('action');
        for (let i = actions.length - 1; i >= 0; i--) {
            actions[i].remove();
        }
        for (let i = 0; i < data.length; i++) {
            let tableRow = document.createElement('tr');
            tableRow.className = 'action';
            for (let j = 0; j < data[i].length; j++) {
                let tableData = document.createElement('td');
                tableData.innerHTML = data[i][j];
                tableRow.appendChild(tableData);
            }
            actionsTable.appendChild(tableRow);
        }
    }

    // Basic structure but is not implemented in Actions_helper.php and tserver.sql dump
    function showResult(str) {
        if (str.length == 0) {
            document.getElementById("livesearch").innerHTML="";
            document.getElementById("livesearch").style.border="0px";
            return;
        }
        let url = "Actions_Helper.php?q=" + str;
        console.log(url);
        $.ajax({
            url: url,
            success: function(result) {
                console.log(result);
                document.getElementById("livesearch").innerHTML = this.responseText;
                document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
            }
        });
    }



</script>
</body>
</html>

