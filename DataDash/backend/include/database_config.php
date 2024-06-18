<?php
header('Access-Control-Allow-Origin: http://127.0.0.1:5501');
header('Access-Control-Allow-Credentials: true');

session_start();

$conn = new mysqli("localhost", "root", "", "datadash");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
