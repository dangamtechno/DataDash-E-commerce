<?php
    require './include/db.php';
    
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $name  = $_POST['name'];
        $email  = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $stmt   =   "INSERT INTO admin (name,phone,email,password) values(?,?,?,?);";
        $prep_stmt  =   $conn->prepare($stmt);
        $prep_stmt->bind_param('ssss',$name,$phone,$email,$password);
        if($prep_stmt->execute()){
            echo json_encode(['admin_registration'=>true]);
        }
        else{
            echo json_encode(['error'=>'something went wrong with insert']);
        }
        $prep_stmt->close();
        exit();
    }
    
    
    
    
    if($_SERVER['REQUEST_METHOD']=='GET'){
        $stmt = 'describe admin';
        $result = $conn->query($stmt);
        $arr = array();

        while($row = $result->fetch_assoc()){
            array_push($arr,$row);
        }
        //remove id field from array
        array_splice($arr, 0, 1);
        //remove registration date from array
        array_splice($arr, 4, 3);
        echo json_encode(['columns'=>  $arr]);
    }
  exit();
?>