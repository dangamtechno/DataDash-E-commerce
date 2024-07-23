<?php
    require './include/db.php';
    //delete banner 
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $banner_id = $_GET['id'];

        // Prepare SQL statement
        $sql = "DELETE FROM banner WHERE id = ?";

        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $banner_id);

        // Attempt to execute the statement
        if ($stmt->execute()) {
            echo json_encode(["deleteSuccess" => "banner deleted successfully"]);
        } else {
            echo json_encode(["error" => "failed to delete"]);

        }
        // Close statement
        $stmt->close();
    }
   exit();
?>