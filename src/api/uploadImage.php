<?php
include "config.php";

// Check if the file and maND are set
if (isset($_FILES['file']) && isset($_POST['maND'])) {
    $filename = $_FILES['file']['name'];
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'pdf');
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $maND = $_POST['maND'];

    // Validate file extension
    if (in_array(strtolower($extension), $allowed_extensions)) {
        $temp_location = $_FILES['file']['tmp_name'];
        $destination = "upload/" . $filename;

        // Move uploaded file to destination
        if (move_uploaded_file($temp_location, $destination)) {
            // Escape filename to prevent SQL injection
            $file_path = mysqli_real_escape_string($conn, $destination);

            // Update database with file path
            $query = "UPDATE nguoi_dung SET anhbia = '$file_path' WHERE maND = '$maND' ";

            if (mysqli_query($conn, $query)) {
                echo json_encode(array("success" => true, "message" => "File uploaded successfully."));
            } else {
                echo json_encode(array("success" => false, "message" => "Error saving file path to database: " . mysqli_error($conn)));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Failed to move uploaded file."));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Invalid file extension."));
    }
} else {
    echo json_encode(array("success" => false, "message" => "File or user ID not provided."));
}
?>