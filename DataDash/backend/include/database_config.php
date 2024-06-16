<?php
header('Access-Control-Allow-Origin: *');

session_start();

$conn = new mysqli("localhost", "root", "", "datadash");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
