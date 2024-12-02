const audioPlayer = document.getElementById('audio-player');
const playPauseButton = document.getElementById('play-pause-button');
const prevButton = document.getElementById('prev-button');
const nextButton = document.getElementById('next-button');
const progressBar = document.getElementById('progress-bar');
const currentTimeDisplay = document.getElementById('current-time');
const totalTimeDisplay = document.getElementById('total-time');
const volumeSlider = document.getElementById('volume-slider');
const songListElement = document.getElementById('song-list');
const uploadForm = document.getElementById('uploadForm');  
const searchBar = document.getElementById('search-bar');

// Array of songs
const songs = [
];
let songIndex = 0;

// Show songs in list
function displaySongList(filter = '') {
    songListElement.innerHTML = '';  // Clear the existing list
    songs.forEach((song, index) => {
        const songTitle = getSongTitle(song);
        
        // Check if the song title includes the filter term
        if (songTitle.toLowerCase().includes(filter.toLowerCase())) {
            const audio = new Audio(song);

            // Load metadata
            audio.addEventListener('loadedmetadata', () => {
                const songDuration = formatTime(audio.duration);

                // Create song list item
                const listItem = document.createElement('li');
                listItem.classList.add('song-item');
                listItem.innerHTML = `
                    <div class="song-info">
                        <strong>${songTitle}</strong> <br>
                        <span>Duration: ${songDuration}</span>
                    </div>
                    <img src="assets/pplay.png" alt="Play" class="play-song-button" data-index="${index}">
                `;

                songListElement.appendChild(listItem);
            });
        }
    });

    // Add event listeners to all play buttons (image buttons)
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('play-song-button')) {
            const newSongIndex = parseInt(e.target.getAttribute('data-index'));
            if (newSongIndex !== songIndex) {
                songIndex = newSongIndex;
                loadSong(songIndex);
                audioPlayer.play();
            }
        }
    });
}

// Event listener for the search bar input
searchBar.addEventListener('input', (e) => {
    const searchTerm = e.target.value;  // Get the current search term
    displaySongList(searchTerm);  // Filter the song list based on the search term
});

// Function to extract the song title from the file path
function getSongTitle(songPath) {
    const parts = songPath.split('/');
    const fileName = parts[parts.length - 1];
    return fileName.replace('.mp3', '');
}

// Function to load the current song
function loadSong(index) {
    audioPlayer.src = songs[index];
    playPauseButton.src = 'assets/pp.png';  // Set play/pause button to play image
    audioPlayer.load();
    audioPlayer.addEventListener('loadedmetadata', () => {
        totalTimeDisplay.textContent = formatTime(audioPlayer.duration);  // Display total song duration
    });
}

// Function to format the time in minutes and seconds
function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
}

//needs to changed to read database for  songs instead of adding them toghether in JS
// Function to fetch songs from the database and update the songs array
function loadSongsFromDatabase() {
    fetch('get_songs.php?' + new Date().getTime())  // Prevent caching with a timestamp query
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const newSongs = data.songs;  // Get the songs from the database response

                // Add new songs to the existing songs array without duplicates
                newSongs.forEach(song => {
                    if (!songs.includes(song)) {
                        songs.push(song);  // Add each song to the existing array if not already present
                    }
                });

                displaySongList();  // Refresh the song list in the UI
            } else {
                alert('Error loading songs: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
} 

// Function to upload a new song
function uploadSong(event) {
    event.preventDefault();  // Prevent the default form submission
    const formData = new FormData(uploadForm);

    fetch('upload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const newSongPath = data.filepath;  // Get the new song path from the response
            songs.push(newSongPath);  // Add the new song to the songs array
            displaySongList();  // Refresh the song list
            alert('Song uploaded successfully!');
        } else {
            alert('Error uploading song: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Call this function when the page loads to fetch songs from the database
document.addEventListener('DOMContentLoaded', loadSongsFromDatabase);

// Event listener for song upload form submission
uploadForm.addEventListener('submit', uploadSong);

// Update song progress bar
function updateProgress() {
    progressBar.value = (audioPlayer.currentTime / audioPlayer.duration) * 100;
    currentTimeDisplay.textContent = formatTime(audioPlayer.currentTime);
}

// Skip through song using the progress bar
progressBar.addEventListener('input', () => {
    audioPlayer.currentTime = (progressBar.value / 100) * audioPlayer.duration;
});

// Play/Pause functionality
playPauseButton.addEventListener('click', () => {
    if (audioPlayer.paused) {
        audioPlayer.play();
    } else {
        audioPlayer.pause();
    }
});

// Skip to the next song
nextButton.addEventListener('click', () => {
    songIndex = (songIndex + 1) % songs.length;
    loadSong(songIndex);
    audioPlayer.play();
    playPauseButton.src = 'assets/pp.png';  // Ensure the play/pause button reflects the playing state
});

// Go back to the previous song
prevButton.addEventListener('click', () => {
    songIndex = (songIndex - 1 + songs.length) % songs.length;
    loadSong(songIndex);
    audioPlayer.play();
    playPauseButton.src = 'assets/pp.png';  // Ensure the play/pause button reflects the playing state
});

// Update the music progress bar as the song plays
audioPlayer.addEventListener('timeupdate', updateProgress);

// Handle volume slider
volumeSlider.addEventListener('input', () => {
    audioPlayer.volume = volumeSlider.value;
});

// Load the first song
loadSong(songIndex);

// Display the song list with metadata
displaySongList();