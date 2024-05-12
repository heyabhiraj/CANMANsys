<?php


define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'can1');

$conn;
/************ DATABASE FUNCTIONS **************/
function connectToDB(){
    global $conn;

    // Create connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}

function closeDB(){
    global $conn;
    $conn->close();
}

function getTables(){
    global $conn;
    $sql = "SHOW TABLES";
    $result = $conn->query($sql) or die("Query failed");

    $tables = [];
    while ($row = $result->fetch_assoc()){
        $tables[] = $row['Tables_in_'.DB_NAME];
    }
    return $tables;
}


connectToDB();

// Function to sanitize user input (prevent SQL injection)
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

$tables = getTables();

?>