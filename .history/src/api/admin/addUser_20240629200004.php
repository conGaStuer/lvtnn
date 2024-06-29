<?php
include "../config.php";
// Ensure POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Assuming you have a function to sanitize input data
    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)));
    }



    try {
        // Create connection


        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement for insertion
        $stmt = $conn->prepare("INSERT INTO nguoi_dung (tenKH, sdt, email) VALUES (:tenKH, :sdt, :email)");

        // Bind parameters
        $stmt->bindParam(':tenKH', $tenKH);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':email', $email);

        // Sanitize and assign POST data to variables
        $tenKH = sanitize($_POST['tenKH']);
        $sdt = sanitize($_POST['sdt']);
        $email = sanitize($_POST['email']);

        // Execute the prepared statement
        $stmt->execute();

        // Respond with success message or inserted user ID
        $response = [
            'status' => 'success',
            'message' => 'User added successfully'
            // Optionally, you can send the new user ID or any other relevant data back
        ];

        echo json_encode($response);

    } catch (PDOException $e) {
        // Handle database connection errors
        $response = [
            'status' => 'error',
            'message' => 'Database connection failed: ' . $e->getMessage()
        ];

        echo json_encode($response);
    }

    // Close connection
    $conn = null;
} else {
    // Handle invalid request method (not POST)
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed']);
}

?>