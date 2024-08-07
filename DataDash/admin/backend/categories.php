<?php
require './include/db.php';
// header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD']==="GET" && isset($_GET['id'])){
    $stmt = "SELECT category_name FROM category where  category_id = ?;";
    $prep_stmt = $conn->prepare($stmt);
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $prep_stmt->bind_param('i',$id);
    if($prep_stmt->execute()) 
    { 
        $result = $prep_stmt->get_result();
        $category = $result->fetch_assoc();
        echo json_encode([ 'category' => $category]);
    }
    else{
        echo json_encode([ 'error' => 'something went wrong' ]);
    }
    $prep_stmt->close();
    exit();
}





if($_SERVER['REQUEST_METHOD']==="GET"){
    $stmt = "SELECT * FROM category;";
    if($result = $conn->query($stmt)){
        $arr = array();
        while($row = $result->fetch_assoc()){
            array_push($arr,$row);
        }
        echo json_encode(['categories' => $arr]);
    }
    else{   
       echo json_encode(['error' => 'something went wrong. try again later.']); 
    }
    exit();
}   
   


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    // Retrieve and sanitize input data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    // Prepare the SQL statement
    $stmt = "UPDATE category SET category_name = ? , status = ? WHERE category_id = ?;";
    
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
    $prep_stmt->bind_param('sii', $name,$status ,$id);
    
    // Execute the statement
    $executeResult = $prep_stmt->execute();
    
    if ($executeResult === false) {
        // Handle the error, log it, etc.
        echo json_encode(['error' => 'Execute statement failed: ' . $prep_stmt->error]);
        exit();
    } else {
       $stmt='SELECT * FROM category  WHERE  category_id = ?;';
       $prep_stmt = $conn->prepare($stmt);
       $id = $_POST['id'];
       $prep_stmt->bind_param('i',$id);
       $prep_stmt->execute();
       if($res = $prep_stmt->get_result()){
           echo json_encode(['category'=>$res->fetch_assoc()]);
       }
    }   
    // Close statement
    $prep_stmt->close();
    exit(); // Exit script after handling the request
}
//post method for inserting a new category
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
    // Prepare the SQL statement
    $stmt = "INSERT INTO category (category_name,status)
    VALUES(?,?)";
    
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
    $prep_stmt->bind_param('si', $name,$status);
    
    // Execute the statement
    if($prep_stmt->execute()){
        echo json_encode(['add_category'=>true]);
    }
    else{
        echo json_encode(['error'=>'something went wrong with insert']);
    }
    $prep_stmt->close();
    exit(); // Exit script after handling the request
}
?>