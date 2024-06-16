<?php
require '../include/database_config.php';
//header('Access-Control-Allow-Origin: *');

if(isset($_POST['search'])){
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    $sql = "SELECT * FROM product JOIN category ON product.category_id = category.id WHERE product.name LIKE ?";
    $prep_stmt = $conn->prepare($sql);
    $searchParam = "%$search%";
    $prep_stmt->bind_param("s", $searchParam);
    $prep_stmt->execute();
    $result = $prep_stmt->get_result();    
    $queryResult = mysqli_num_rows($result);
    $arr  = array();
    if($queryResult > 0){
        while($row = mysqli_fetch_assoc($result)){
            array_push($arr, $row);
        }
        echo json_encode(['search' => $arr]); 
    }
    else{
        echo json_encode(['error' => []]); // Return empty array if no results found
    }
}

exit();
?>
