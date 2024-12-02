<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tuneify</title>
    <link rel="icon" type="image/x-icon" href="assets\logo.ico">
    <link rel="stylesheet" href="main.css">
</head>
<body>

<div class="topnav">
    <a href="index.php">
        <img src="assets/logo.png" alt="Logo">
    </a>
    <a href="#news">News</a>
    <a href="#contact">Contact</a>
    <a href="#about">About</a>
</div>

<button id="openModalBtn">Open Login Page</button>
    <div id="loginModal">
        <div class="modal-content">
            <span class="close" id="closeModalBtn">&times;</span>
                <h2>Login</h2>
            <form method="POST">
                <label for="username">Username:</label>
                <input type="text" id="userType" name="userType" required><br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>
                    <div class="login-container">
                        <button class="login-button" type="submit" value="Submit" name="submit1">Login</button>
                    </div>
            </form>
        </div>
    </div>

    <button id="openRegisterModalBtn">Open Registration Page</button>
<div id="registerModal" style="display: none;">
    <div class="modal-content">
        <span class="close" id="closeRegisterModalBtn">&times;</span>
        <h2>Register</h2>
        <form method="POST">
            <label for="uname">Username:</label>
            <input type="text" id="uname" name="uname" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="confirm_email">Confirm Email:</label>
            <input type="email" id="confirm_email" name="confirm_email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required><br><br>

            <div class="register-container">
                <button class="register-button" type="submit" name="register">Register</button>
            </div>
        </form>
    </div>
</div>

    <script>
        var modal = document.getElementById("loginModal");  

        var btn = document.getElementById("openModalBtn");

        var closeBtn = document.getElementById("closeModalBtn");

        btn.onclick = function() {
            modal.style.display = "block";
        }

        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

    var registerModal = document.getElementById("registerModal");
    var openRegisterBtn = document.getElementById("openRegisterModalBtn");
    var closeRegisterBtn = document.getElementById("closeRegisterModalBtn");

    // Open the registration modal
    openRegisterBtn.onclick = function() {
        registerModal.style.display = "block";
    }

    // Close the registration modal
    closeRegisterBtn.onclick = function() {
        registerModal.style.display = "none";
    }

    // Close the modal when clicking outside of the modal
    window.onclick = function(event) {
        if (event.target == registerModal) {
            registerModal.style.display = "none";
        }
    }
    </script>
    <?php
include "db.php";

if (isset($_POST['submit1'])){
    $usertype = $_POST['userType'];
    $password = $_POST['password'];
    $hashedpassword = sha1($password);
#admin password
    if ($usertype == "uname"){
        header( "Location: Rating.php" );
        exit();
    }else{
      $query = "SELECT * FROM users WHERE password = '$hashedpassword'";
      $result = mysqli_query($conn,$query);
        if ($result = mysqli_num_rows($result) > 0){
            $_SESSION['userType'] = $usertype;
            echo "<script>alert('login succsessful')</script>";
            header( "Location: menu.php" );
            echo "<script>window.location.href = 'Results.php;</script>";
      }
        else{
            echo "<script>alert('login failed')</script>";
      }
    }

    
}
if (isset($_POST['register'])) {
    $uname = mysqli_real_escape_string($conn, $_POST['uname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $confirm_email = mysqli_real_escape_string($conn, $_POST['confirm_email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validate username length (2-25 characters)
    if (strlen($uname) < 2 || strlen($uname) > 25) {
        echo "<script>alert('Username must be between 2 and 25 characters!');</script>";
    }
    // Validate email matching
    elseif ($email !== $confirm_email) {
        echo "<script>alert('Emails do not match!');</script>";
    }
    // Validate password matching
    elseif ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    }
    // Validate password length (5-20 characters)
    elseif (strlen($password) < 5 || strlen($password) > 20) {
        echo "<script>alert('Password must be between 5 and 20 characters!');</script>";
    }
    // Validate that password contains only letters and numbers
    elseif (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
        echo "<script>alert('Password can only contain letters and numbers!');</script>";
    }
    // Validate that username contains only letters and numbers
    elseif (!preg_match('/^[a-zA-Z0-9]+$/', $uname)) {
        echo "<script>alert('Username can only contain letters and numbers!');</script>";
    }
    else {
        $hashedPassword = sha1($password);

        // Check if the username or email already exists
        $checkQuery = "SELECT * FROM users WHERE uname = '$uname' OR email = '$email'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            // If username or email exists
            echo "<script>alert('Username or Email already exists! Please choose another one.');</script>";
        } else {
            // Insert new user into the database
            $query = "INSERT INTO users (uname, email, password) VALUES ('$uname', '$email', '$hashedPassword')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                echo "<script>alert('Registration successful! You can now login.');</script>";
            } else {
                echo "<script>alert('Registration failed. Please try again.');</script>";
            }
        }
    }
}
?>
</body>
</html>