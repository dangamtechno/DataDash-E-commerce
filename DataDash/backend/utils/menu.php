<?php
//session_start();

require '../include/database_config.php';

header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD']==="GET"){
    $stmt = "SELECT category_name FROM category WHERE status=1;";
    if($result = $conn->query($stmt)){
        $arr = array();
        while($row = $result->fetch_assoc()){
            $name = $row['Name'];
            array_push($arr,$name);
        }
        echo json_encode(['categories' => $arr]);
    }
    else{   
       echo json_encode(['error' => 'something went wrong. try again later.']); 
    }

}
exit();
