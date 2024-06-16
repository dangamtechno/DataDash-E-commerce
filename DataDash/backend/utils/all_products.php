<?php
require '../include/database_config.php';
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD']==="GET"){
    $stmt = "SELECT * FROM product WHERE status=1;";
    if($result = $conn->query($stmt)){
        $arr = array();
        while($rowArray = $result->fetch_assoc()){
            array_push($arr,$rowArray);
        }
        echo json_encode(['products' => $arr]);
    }
    else{   
       echo json_encode(['error' => 'something went wrong. try again later.']); 
    }
}
    exit();