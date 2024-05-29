<?php
$conn = new mysqli("localhost", "root", "", "datadash");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->close();
