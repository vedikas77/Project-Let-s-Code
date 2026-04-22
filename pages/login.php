<?php
session_start();
// Use your actual DB password here
$conn = new mysqli("localhost", "root", "", "letscode");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Change 'email' to 'username' to match your form input name
    $username = $conn->real_escape_string($_POST['username']); 
    $password = $_POST['password'];

    // Search by username instead of email
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Match the password (use password_verify if you hashed it in register.php)
        if (password_verify($password, $row['password']) || $password == $row['password']) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: myhome.html"); // Redirect to your main course page
        } else {
            echo "<script>alert('Wrong password!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No user found with this username!'); window.history.back();</script>";
    }
}
?>
