<?php

include ("config.php");

$data = json_decode(file_get_contents("php://input"));

// Check if commentId is provided
if (!empty($data->commentId)) {
    $commentId = $data->commentId;

    // Create database connection
    $database = new Database();
    $db = $database->getConnection();

    // Perform delete operation (Example query, adjust as per your database schema)
    $query = "DELETE FROM comments WHERE comment_id = :commentId";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':commentId', $commentId);

    if ($stmt->execute()) {
        // Return success response
        http_response_code(200);
        echo json_encode(array("message" => "Comment deleted successfully"));
    } else {
        // Return error response
        http_response_code(500);
        echo json_encode(array("message" => "Failed to delete comment"));
    }
} else {
    // Return error if commentId is not provided
    http_response_code(400);
    echo json_encode(array("message" => "Missing commentId"));
}
?>