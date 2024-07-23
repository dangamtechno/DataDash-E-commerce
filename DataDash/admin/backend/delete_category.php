<?php
    require './include/db.php';
    //delete banner 
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $category_id = $_GET['id'];

        // Prepare SQL statement
        $sql = "DELETE FROM category WHERE id category_id= ?";

        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $category_id);

        // Attempt to execute the statement
        if ($stmt->execute()) {
            echo json_encode(["deleteSuccess" => "catgeory deleted successfully"]);
        } else {
            echo json_encode(["error" => "delete failed"]);

        }
        // Close statement
        $stmt->close();
    }
   exit();
?>