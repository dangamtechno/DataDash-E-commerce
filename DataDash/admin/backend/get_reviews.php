

<?php
//create  php to  get a   orderid for currently   viewed  prod    based   on  user    id
require './include/db.php';
// header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $username =  $_POST['username'];
    $review_text = $_POST['review'];
    $rating = $_POST['rating'];
    $stmt = "INSERT INTO reviews (reviewText,rating,user_id)
    SELECT ?, ?, id FROM users WHERE email = ?";
    $prep_stmt = $conn->prepare($stmt);
    //somthing to do with the values being passed in needs to happen here to 
    $prep_stmt->bind_param('sis',$review_text,$rating,$username);
    if($prep_stmt->execute()){
        //$arr = array();
        //while($rowArray= $result->fetch_assoc()){
        //array_push($arr,$result->fetch_assoc());
        //}
        echo json_encode(['submit_review' => true]);
    }          
    else{
        echo json_encode(['error' => "Something went wrong"]);
    }
    $prep_stmt->close();
    exit();
}


if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])){
    $stmt = "SELECT reviews.*,users.fname,users.lname FROM reviews
             Join ordered_item on reviews.order_id = ordered_item.order_id 
             join users on reviews.user_id = users.id
             WHERE ordered_item.product_id=?;";
    $prep_stmt = $conn->prepare($stmt);
    $id = $_GET['id'];
    $prep_stmt->bind_param('i',$id);
    $prep_stmt->execute();
    if($result = $prep_stmt->get_result()){
        $arr = array();
        while($rowArray= $result->fetch_assoc()){
           array_push($arr,$rowArray);
        }
        echo json_encode(['reviews' => $arr]);
    }          
    else{
        echo json_encode(['error' => "Something went wrong"]);
    }
    exit();
}
