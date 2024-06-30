<?php
require './include/db.php';
// header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD']== "GET" && isset($_GET['id'])){   
    $stmt='SELECT * FROM product  WHERE  product_id = ?;';
    $prep_stmt = $conn->prepare($stmt);
    $id = $_GET['id'];
    $prep_stmt->bind_param('i',$id);
    $prep_stmt->execute();
    if($res = $prep_stmt->get_result()){
        echo json_encode(['product'=>$res->fetch_assoc()]);
    }
    else{
        echo(['error'=>'something went wrong']);
    }
    exit();
 }


if($_SERVER['REQUEST_METHOD']==="GET"){
    $stmt = "SELECT * FROM product;";
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
    exit();
}


// Check if it's a POST request and 'id' parameter is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    // Retrieve and sanitize input data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
    $desc = isset($_POST['description']) ? $_POST['description'] : '';
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    // Prepare the SQL statement
    $stmt = "UPDATE product SET name = ? ,description = ? , status = ? WHERE product_id = ?;";
    
    // Prepare and execute the statement
    $prep_stmt = $conn->prepare($stmt);
    if (!$prep_stmt) {
        // Handle prepare error
        echo json_encode(['error' => 'Prepare statement failed: ' . $conn->error]);
        exit();
    }
    
    if (!$prep_stmt) {
        // Handle prepare error
        echo json_encode(['error' => 'Prepare statement failed: ' . $conn->error]);
        exit();
    }
    
    // Bind parameters
    $prep_stmt->bind_param('ssii', $name,$desc,$status ,$id);
    
    // Execute the statement
    $executeResult = $prep_stmt->execute();
    
    if ($executeResult === false) {
        // Handle the error, log it, etc.
        echo json_encode(['error' => 'Execute statement failed: ' . $prep_stmt->error]);
        exit();
    } else {
       $stmt='SELECT * FROM product  WHERE  id = ?;';
       $prep_stmt = $conn->prepare($stmt);
       $id = $_POST['id'];
       $prep_stmt->bind_param('i',$id);
       $prep_stmt->execute();
       if($res = $prep_stmt->get_result()){
           echo json_encode(['product'=>$res->fetch_assoc()]);
       }
    }
    
    // Close statement
    $prep_stmt->close();
    exit(); // Exit script after handling the request
}






?>