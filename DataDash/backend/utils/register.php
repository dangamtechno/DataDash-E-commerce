<?php
require '../include/database_config.php';

    if($_SERVER['REQUEST_METHOD']=='GET'){
        $stmt = 'describe users';
        $result = $conn->query($stmt);
        $arr = array();

        while($row = $result->fetch_assoc()){
            array_push($arr,$row);
        }
        //remove id field from array
        array_splice($arr, 0, 1);
        //remove registration date from array
        array_splice($arr, 5, 1);
        array_splice($arr,6,1);
        echo json_encode(['columns'=>  $arr]);
        exit();
    }
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $fname  = $_POST['first_name'];
        $lname  = $_POST['last_name'];
        $email  = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password_hash'];
        $stmt   =   "INSERT INTO users (first_name,last_name,phone,email,password_hash) values(?,?,?,?,?);";
        $prep_stmt  =   $conn->prepare($stmt);
        $prep_stmt->bind_param('sssss',$fname,$lname,$phone,$email,$password);
        if($prep_stmt->execute()){
            echo json_encode(['registration'=>true]);
        }
        else{
            echo json_encode(['error'=>'something went wrong with insert']);
        }
        $prep_stmt->close();
        exit();
    }
?>