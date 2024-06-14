<?php
// deleteCate.php

// Include database connection
require_once '../config.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the POST data
    $postData = file_get_contents("php://input");
    $request = json_decode($postData, true);

    // Check if 'maNN' parameter is set in the POST request
    if (isset($request['maNN'])) {
        // Get the category ID from the POST request
        $maNN = $request['maNN'];

        // Start transaction
        $conn->begin_transaction();

        try {
            // Prepare the SQL statement for deleting books in the category
            $stmt1 = $conn->prepare("DELETE FROM nn_sach WHERE maNN = ?");
            $stmt1->bind_param("i", $maNN);
            $stmt1->execute();

            // Prepare the SQL statement for deleting the category
            $stmt2 = $conn->prepare("DELETE FROM ngon_ngu WHERE maNN = ?");
            $stmt2->bind_param("i", $maNN);
            $stmt2->execute();

            // Commit transaction
            $conn->commit();

            // Check if any category was deleted
            if ($stmt2->affected_rows > 0) {
                // Respond with a success message
                echo json_encode(["status" => "success", "message" => "Category and related books deleted successfully."]);
            } else {
                // Respond with a failure message if no category was deleted
                echo json_encode(["status" => "error", "message" => "Category not found or already deleted."]);
            }

            // Close the prepared statements
            $stmt1->close();
            $stmt2->close();
        } catch (Exception $e) {
            // Rollback transaction if any error occurs
            $conn->rollback();
            // Respond with an error message
            echo json_encode(["status" => "error", "message" => "Failed to delete category and related books."]);
        }
    } else {
        // Respond with an error message if 'maNN' parameter is missing
        echo json_encode(["status" => "error", "message" => "'maNN' parameter is required."]);
    }
} else {
    // Respond with an error message if the request method is not POST
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

// Close the database connection
$conn->close();
?>