<?php
// Include the database connection from db.php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['songFile']) && $_FILES['songFile']['error'] === UPLOAD_ERR_OK) {
        // Define the directory where files will be uploaded
        $uploadDir = 'music/';

        // Get file info
        $fileTmpPath = $_FILES['songFile']['tmp_name'];
        $fileName = $_FILES['songFile']['name'];
        $fileSize = $_FILES['songFile']['size'];
        $fileType = $_FILES['songFile']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Allowed file extensions
        $allowedfileExtensions = array('mp3');
        
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Define the path to upload the file using the original file name
            $uploadFilePath = $uploadDir . $fileName;

            // Move the file to the destination directory
            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                // Insert the song path into the database
                $sql = "INSERT INTO songs (filepath) VALUES ('$uploadFilePath')";
                if ($conn->query($sql) === TRUE) {
                    echo json_encode(['success' => true, 'filepath' => $uploadFilePath]);  // Return the file path
                } else {
                    echo json_encode(['success' => false, 'message' => 'Database insert failed: ' . $conn->error]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'File move failed.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only MP3 files are allowed.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No file uploaded or an upload error occurred.']);
    }
}

// Close the database connection at the end
$conn->close();
?>