<?php
require '../include/database_config.php';
//$conn = new mysqli("localhost", "root", "", "datadash");
header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD']=="GET"){
   $stmt = "SELECT * FROM banner WHERE status = 1;";
    if($result = $conn->query($stmt)){
        $arr = array();
        while($rowArray = $result->fetch_assoc()){
            array_push($arr,$rowArray);
        }
        echo json_encode(['banners'=> $arr]);
    }
    else{
       echo json_encode(['error'=> 'something went wrong']);
    }
   exit();
}