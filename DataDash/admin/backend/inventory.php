<?php
require './include/db.php';
// header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD']== "GET" ){
    $stmt = "SELECT * FROM inventory;";
    if($result = $conn->query($stmt)){
        $arr = array();
        while($rowArray= $result->fetch_assoc()){
           array_push($arr,$result->fetch_assoc());
        }
        echo json_encode(['inventory' => $arr]);
    }          
    else{
        echo json_encode(['error' => "Something went wrong"]);
    }
}
