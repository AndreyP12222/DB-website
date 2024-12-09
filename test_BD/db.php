<?php

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "university";

// Підключення до бази даних
$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}
?>
