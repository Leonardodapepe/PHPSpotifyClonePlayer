<?php
// get_songs.php
header('Content-Type: application/json');

// Include the database connection file
include 'db.php';

try {
    // Query to get all songs from the 'songs' table
    $stmt = $pdo->prepare("SELECT filepath FROM songs");
    $stmt->execute();
    
    // Fetch the file paths as an array
    $songs = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Return the songs as JSON
    echo json_encode([
        'success' => true,
        'songs' => $songs
    ]);
} catch (PDOException $e) {
    // Return an error if the query fails
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching songs: ' . $e->getMessage()
    ]);
}
?>