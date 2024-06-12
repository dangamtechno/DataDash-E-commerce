<?php
require '../include/database_config.php';
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD']== "GET" && isset($_GET['id'])){
    $stmt = "SELECT reviews.*,users.fname,users.lname FROM reviews
             Join ordered_item on reviews.order_id = ordered_item.order_id 
             join users on reviews.user_id = users.id
             WHERE ordered_item.product_id=?;";
    $prep_stmt = $conn->prepare($stmt);
    $id = $_GET['id'];
    $prep_stmt->bind_param('i',$id);
    $prep_stmt->execute();
    if($result = $prep_stmt->get_result()){
        $arr = array();
        while($rowArray= $result->fetch_assoc()){
           array_push($arr,$rowArray);
        }
        echo json_encode(['reviews' => $arr]);
    }          
    else{
        echo json_encode(['error' => "Something went wrong"]);
    }
}
