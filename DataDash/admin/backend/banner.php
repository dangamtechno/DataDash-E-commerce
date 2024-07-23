<?php
require './include/db.php';
// header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD']== "GET" && isset($_GET['id'])){   
   $stmt='SELECT * FROM banner  WHERE  id = ?;';
   $prep_stmt = $conn->prepare($stmt);
   $id = $_GET['id'];
   $prep_stmt->bind_param('i',$id);
   $prep_stmt->execute();
   if($res = $prep_stmt->get_result()){
       echo json_encode(['banner'=>$res->fetch_assoc()]);
   }
   else{
       echo(['error'=>'something went wrong']);
   }
   exit();
}

if($_SERVER['REQUEST_METHOD']=="GET"){
   $stmt = "SELECT * FROM banner;";
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




// Check if it's a POST request and 'id' parameter is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    // Retrieve and sanitize input data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
    $desc = isset($_POST['description']) ? $_POST['description'] : '';
    $image = isset($_POST['image']) ? $_POST['image'] : '';
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    // Prepare the SQL statement
    $stmt = "UPDATE banner SET name = ? ,description = ? ,image = ?,status = ? WHERE id = ?;";
    
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
    $prep_stmt->bind_param('sssii', $name,$desc,$image,$status ,$id);
    
    // Execute the statement
    $executeResult = $prep_stmt->execute();
    
    if ($executeResult === false) {
        // Handle the error, log it, etc.
        echo json_encode(['error' => 'Execute statement failed: ' . $prep_stmt->error]);
        exit();
    } else {
       $stmt='SELECT * FROM banner  WHERE  id = ?;';
       $prep_stmt = $conn->prepare($stmt);
       $id = $_POST['id'];
       $prep_stmt->bind_param('i',$id);
       $prep_stmt->execute();
       if($res = $prep_stmt->get_result()){
           echo json_encode(['banner'=>$res->fetch_assoc()]);
       }
    }
    
    // Close statement
    $prep_stmt->close();
    exit(); // Exit script after handling the request
}

//delete banner 
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $banner_id = $_GET['id'];

    // Prepare SQL statement
    $sql = "DELETE FROM banners WHERE id = ?";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $banner_id);

    // Attempt to execute the statement
    if ($stmt->execute()) {
        echo "Banner deleted successfully.";
    } else {
        echo "Error deleting banner: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

//post method for inserting a new banner
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
    $desc = isset($_POST['description']) ? $_POST['description'] : '';
    $image = isset($_POST['image']) ? $_POST['image'] : '';
    // Prepare the SQL statement
    $stmt = "INSERT INTO banner (name, description,image,status)
    VALUES(?, ?, ?, ?)";
    
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
    $prep_stmt->bind_param('sssi', $name,$desc,$image,$status);
    
    // Execute the statement
    if($prep_stmt->execute()){
        echo json_encode(['add_banner'=>true]);
    }
    else{
        echo json_encode(['error'=>'something went wrong with insert']);
    }
    $prep_stmt->close();
    exit(); // Exit script after handling the request
}

?>
