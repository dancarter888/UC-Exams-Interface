<?php
    if (isset($_POST['test_date'])) {
        session_start();

        $_SESSION['test_date'] = $_POST['test_date'];
        $_SESSION['test_name'] = $_POST['test_name'];
        $_SESSION['test_room'] = $_POST['test_room'];
        $_SESSION['test_stime'] = $_POST['test_stime'];
        $_SESSION['test_etime'] = $_POST['test_etime'];

        echo $_SESSION['test_date'];
    } else {
        header("Location: http://localhost/info263-project/source/pages/Create.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../css/login.css">
        <meta charset="UTF-8">
        <title>Create</title>
    </head>
    <body>
        <h1> Create Test Step 2 </h1>

        <br > <br > <br >

        <form name="Type" action="Create3.php" method="POST">
            <table id="clusters">
                <tr>
                    <th> Select </th>
                    <th> Type </th>
                    <th> Description </th>
                </tr>
            </table>

            <input type="submit" value="Next" />
        </form>

        <script>
            nocache = "&nocache=1";
            request = new asyncRequest()
            request.open("GET", "Create_Helper.php?item=Clusters" + nocache, true)
            request.onreadystatechange = function()
            {
                if (this.readyState == 4)
                {
                    if (this.status == 200)
                    {
                        if (this.responseText != null)
                        {
                            let table = document.getElementById('clusters');
                            console.log(this.responseText);
                            // NEED TO CATCH ERROR IF PARSE FAILS
                            let clusters = JSON.parse(this.responseText);
                            for (let i=0; i<clusters.length; i++) {
                                let row = table.insertRow(i+1);
                                let idCell = row.insertCell(0);
                                let nameCell = row.insertCell(1);
                                let descripCell = row.insertCell(2);

                                let radioBut = document.createElement('input');
                                radioBut.type = "radio";
                                radioBut.name = "test_type";
                                radioBut.value = clusters[i][1];
                                idCell.appendChild(radioBut);

                                nameCell.innerHTML = clusters[i][1];
                                descripCell.innerHTML = clusters[i][2];
                            }
                        }
                        else alert("Communication error: No data received")
                    }
                    else alert( "Communication error: " + this.statusText)
                }
            }
            request.send(null)
            function asyncRequest()
            {
                let request;
                try
                {
                    request = new XMLHttpRequest();
                }
                catch(e1)
                {
                    try
                    {
                        request = new ActiveXObject("Msxml2.XMLHTTP");
                    }
                    catch(e2)
                    {
                        try
                        {
                            request = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch(e3)
                        {
                            request = false
                        }
                    }
                }
                return request
            }
        </script>
    </body>
</html>