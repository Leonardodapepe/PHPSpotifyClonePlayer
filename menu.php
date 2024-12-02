<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tuneify</title>
    <link rel="icon" type="image/x-icon" href="assets\logo.ico">
    <link rel="stylesheet" href="menu.css">
</head>
<body>
<div class="topnav">
    <a href="index.php">
        <img src="assets/logo.png" alt="Logo">
    </a>
    <a href="#news">News</a>
    <a href="#about">About</a>
</div>

<div class="player">
    <audio id="audio-player" src="music/Egzod, Maestro Chives, Neoni - Royalty [NCS Release].mp3"></audio>

    <div class="controls">
        <img id="prev-button" src="assets/backwards.png" alt="Previous" class="control-button">
        <img id="play-pause-button" src="assets/pp.png" alt="Play" class="control-button">
        <img id="next-button" src="assets/forwards.png" alt="Next" class="control-button">
    </div>

    <input type="range" id="progress-bar" value="0" max="100" class="progress-bar">

    <div class="time">
        <span id="current-time">0:00</span> /
        <span id="total-time">0:00</span>
    </div>
    <div class="volume-control">
        <label for="volume-slider">Volume</label>
        <input type="range" id="volume-slider" min="0" max="1" step="0.01" value="1" class="volume-slider">
    </div>
</div>

<!-- Song list with custom background color -->
<div class="song-list-container">
    <h3>Song List</h3>
    <!-- Search bar to filter the songs -->
    <input type="text" id="search-bar" placeholder="Search for a song..." class="search-bar">
    <ul id="song-list"></ul>
</div>

<form id="uploadForm" enctype="multipart/form-data" method="POST">
    <label for="songFile">Upload your song (MP3):</label>
    <input type="file" id="songFile" name="songFile" accept=".mp3">
    <input type="submit" value="Upload">
</form>


<script src="music.js"></script>
<!-- File upload -->
<script>
    // Handle song upload via AJAX
    document.getElementById('uploadForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData();
        const fileInput = document.getElementById('songFile');
        const file = fileInput.files[0];

        if (file) {
            formData.append('songFile', file);

            // Send the file to the server using AJAX
            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add the new song to the song list
                    songs.push(data.filepath);  // Push the song path to the songs array
                    displaySongList();          // Refresh the song list
                    alert('Song uploaded successfully!');
                } else {
                    alert('Failed to upload song.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during the upload.');
            });
        }
    });
</script>
</body>
</html>