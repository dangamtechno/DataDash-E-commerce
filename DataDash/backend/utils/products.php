<?php
require '../include/database_config.php';
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD']==="GET"
   && isset($_GET['category'])){
   $stmt = "SELECT * FROM product WHERE status = 1 AND category_id = (SELECT id FROM category WHERE name = ?);";
   $prep_stmt = $conn->prepare($stmt);
   $category = $_GET['category'];
   $prep_stmt->bind_param('s',$category);
   $prep_stmt->execute();
   if($result = $prep_stmt->get_result()){
    $arr = array();
    while($rowArray = $result->fetch_assoc()){
        array_push($arr,$rowArray);
    }
    echo json_encode(['products'=> $arr]); 
   }
   else{
      echo json_encode(['error'=>'something went wrong.']);
   }
}
   $prep_stmt->close();
   exit();