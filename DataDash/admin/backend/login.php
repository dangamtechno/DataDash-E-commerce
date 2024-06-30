<?php
require './include/db.php';
// header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD']=='GET' && isset($_GET['q'])){
   //look for session variable a name
   if (isset($_SESSION['logged_admin'])){
        echo json_encode(['admin'=> $_SESSION['logged_admin']]);
   }
   else{
        echo json_encode(['error'=>'unauthorized']);
   }
   exit();

}

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(isset($_SESSION['logged_admin'])){
        session_unset();
        session_destroy();
        echo json_encode(['logout' => true]);
    }
    else{
        echo json_encode(['logout' => false]);
    }
    exit();

}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $username = $_POST['email'];
    $password = $_POST['password_hashed'];

    $stmt = 'select * from admin where email = ? and password = ?';
    $prep_stmt = $conn->prepare($stmt);
    $prep_stmt->bind_param('ss',$username,$password);    
    $prep_stmt->execute();
    $result = $prep_stmt->get_result();
    if( $admin_array = $result->fetch_assoc()){
        $_SESSION['logged_admin']['name'] = $admin_array['email'];
        $_SESSION['logged_admin']['id'] = $admin_array['admin_id'];
        echo json_encode([ 'admin' => $_SESSION['logged_admin']]);
    }
    else{
        echo json_encode([ 'error' => 'invalid username or password' ]);
    }
    $prep_stmt->close();
    exit();

}
?>