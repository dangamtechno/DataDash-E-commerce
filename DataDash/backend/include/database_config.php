<?php
//update name to schema name
$database_name = "datadash";
$database_password = "";
$conn = new mysqli("localhost:3306","root",$database_password,$database_name);
if($conn->connect_errno){
    echo json_encode(['error'=>$conn->connect_error]);
    exit();
}
