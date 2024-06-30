<?php
require './include/db.php';
// header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_GET['id'])){
    $stmt = "INSERT INTO reviews (reviewText,rating)
    values (?,?);";
    $prep_stmt = $conn->prepare($stmt);
    //somthing to do with the values being passed in needs to happen here to 
    $id = $_GET['id'];
    $prep_stmt->bind_param('i',$id);
    
    $prep_stmt->execute();
    if($result = $prep_stmt->get_result()){
        //$arr = array();
        //while($rowArray= $result->fetch_assoc()){
        //array_push($arr,$result->fetch_assoc());
        //}
        echo json_encode(['review' => $result->fetch_assoc()['reviewText']]);
    }          
    else{
        echo json_encode(['error' => "Something went wrong"]);
    }
}
