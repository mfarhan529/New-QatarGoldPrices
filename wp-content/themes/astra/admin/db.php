<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$dbname = "qatar"; // change if your DB name is different

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else {
    echo "Connected successfully";
}
?>
