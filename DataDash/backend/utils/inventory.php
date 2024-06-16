<?php
require '../include/database_config.php';
header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD']== "GET" && isset($_GET['id'])){
    $stmt = "SELECT quantity FROM inventory where product_id = ?;";
    $prep_stmt = $conn->prepare($stmt);
    $id = $_GET['id'];
    $prep_stmt->bind_param('i',$id);
    $prep_stmt->execute();
    if($result = $prep_stmt->get_result()){
        //$arr = array();
        //while($rowArray= $result->fetch_assoc()){
        //array_push($arr,$result->fetch_assoc());
        //}
        echo json_encode(['inStock' => $result->fetch_assoc()['quantity']]);
    }          
    else{
        echo json_encode(['error' => "Something went wrong"]);
    }

}
?>






