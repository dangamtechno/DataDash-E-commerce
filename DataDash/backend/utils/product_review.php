<?php
require '../include/database_config.php';
if($_SERVER['REQUEST_METHOD'] == "POST"){

    // Select user_id based on username
    $username = $_POST['user_name'];
    $stmt_select = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt_select->execute([$username]);
    $user_id = $stmt_select->get_result();

    if ($user_id) {
        $id  = $_POST['product_id'];
        $review  = $_POST['review'];
        $rating = $_POST['rating'];
        $stmt = "INSERT INTO reviews (product_id, user_id, review_text, rating) VALUES (?, ?, ?, ?);";
        $prep_stmt  =   $conn->prepare($stmt);
        $prep_stmt->bind_param('iisi',$user_id,$id,$review,$rating);
        if($prep_stmt->execute()){
            echo json_encode(['product_review'=>true]);
        }
        else{
            echo json_encode(['error'=>'something went wrong with insert']);
        }
        $prep_stmt->close();
        exit();
        }
        else{
            exit();
        }
    }
    ?>