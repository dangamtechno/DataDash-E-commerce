<?php
require './include/db.php';

if($_SERVER['REQUEST_METHOD']=='GET'){
    $stmt = 'describe product';
    $result = $conn->query($stmt);
    $arr = array();

    while($row = $result->fetch_assoc()){
        array_push($arr,$row);
    }
    //remove id field from array
    array_splice($arr, 0, 1);
    //remove registration date from array
    array_splice($arr, 7, 8);
    echo json_encode(['columns'=>  $arr]);

    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $desc = isset($_POST['description']) ? $_POST['description'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : 0;
    $image  = isset($_POST['image']) ? $_POST['image'] : '';
    
    $stmt   =   "INSERT INTO product (name,description,price,image,category_id,status) values(?,?,?,?,?,?);";
    $prep_stmt  =   $conn->prepare($stmt);
    $prep_stmt->bind_param('ssdsii',$name,$desc,$price,$image,$category,$status);
    if($prep_stmt->execute()){
        echo json_encode(['add_product'=>true]);
    }
    else{
        echo json_encode(['error'=>'something went wrong with insert']);
    }
    $prep_stmt->close();
    exit();
}





?>