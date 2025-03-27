<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "movie_recommendation";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
