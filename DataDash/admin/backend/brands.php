<?php
require './include/db.php';
// header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD']==="GET" && isset($_GET['id'])){
    $stmt = "SELECT brand_name FROM brands where  brand_id = ?;";
    $prep_stmt = $conn->prepare($stmt);
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $prep_stmt->bind_param('i',$id);
    if($prep_stmt->execute()) 
    { 
        $result = $prep_stmt->get_result();
        $category = $result->fetch_assoc();
        echo json_encode([ 'brands' => $brand]);
    }
    else{
        echo json_encode([ 'error' => 'something went wrong' ]);
    }
    $prep_stmt->close();
    exit();
}





if($_SERVER['REQUEST_METHOD']==="GET"){
    $stmt = "SELECT * FROM brands;";
    if($result = $conn->query($stmt)){
        $arr = array();
        while($row = $result->fetch_assoc()){
            array_push($arr,$row);
        }
        echo json_encode(['brands' => $arr]);
    }
    else{   
       echo json_encode(['error' => 'something went wrong. try again later.']); 
    }
    exit();
}   
   
?>