<?php
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Credentials: true');
session_start(['cookie_samesite'=>'None','cookie_secure' => true]);
$conn = new mysqli("localhost:3306","root","","datadash");


if($conn->connect_errno){
    echo json_encode(['error'=>$conn->connect_error]);
    exit();
}