<?php
require '../include/database_config.php';

// header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD']=='GET' && isset($_GET['q'])){
   //look for session variable a name
   if (isset($_SESSION['logged_user'])){
        echo json_encode(['user'=> $_SESSION['logged_user']]);
   }
   else{
        echo json_encode(['user'=>'guest']);
   }
   exit();
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(isset($_SESSION['logged_user'])){
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

    $stmt = 'select * from users where email = ? and password_hash = ?';
    $prep_stmt = $conn->prepare($stmt);
    $prep_stmt->bind_param('ss',$username,$password);    
    $prep_stmt->execute();
    $result = $prep_stmt->get_result();
    if( $user_array = $result->fetch_assoc()){
        $_SESSION['logged_user']['name'] = $user_array['email'];
        $_SESSION['logged_user']['id'] = $user_array['user_id'];
        echo json_encode([ 'user' => $_SESSION['logged_user']]);
    }
    else{
        echo json_encode([ 'error' => 'invalid username or password' ]);
    }
    $prep_stmt->close();
    exit();

}
?>