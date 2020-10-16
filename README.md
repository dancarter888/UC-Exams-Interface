# INFO263 Project

## Requirements
You will need to have installed:
 - MySQL Workbench
 - AMPPS
 
 ## Instructions
 #####<i> This section assumes you have the above programs installed.</i>
 
 Place the info263-project folder into the {path to Ampps folder}\Ampps\www.  
 Run AMPPS.  
 Next open MySQL Workbench and click Database > Connect to Database...
 Enter the following:
 Connection Method: Standard (TCP/IP)
 Hostname: 127.0.0.1
 Port: 3306
 Username: root
 Click OK.
 Enter "mysql" as the Password and click OK.
 Create a schema named '<b>tserver</b>'.  
 Then open the .sql file located in the info263-project folder by going File > Open SQL Script.  
 Execute this script with the tserver schema selected in order to populate the database.
 
 The database has now been created and populated.   
 To open the webpage open your web browser and 
 type into the search bar '<i>localhost/info263-project/source/pages/Events.php</i>'.