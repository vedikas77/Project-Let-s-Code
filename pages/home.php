<?php
session_start();

// Enable full error reporting to see the problem
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "letscode");

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Check if we have the session ID (Verified as 6 in your screenshot)
if (!isset($_SESSION['user_id'])) {
    die("❌ Error: No user session found. Please log in again.");
}

if (isset($_POST['course_name'])) {
    $uid = $_SESSION['user_id']; 
    $course = $conn->real_escape_string($_POST['course_name']);
    $redirect = $_POST['redirect_to'];

    // This is the exact query that worked for you manually
    $sql = "INSERT INTO languages (user_id, course_name) VALUES ($uid, '$course')";

    if ($conn->query($sql) === TRUE) {
        // SUCCESS: Go to the course page
        header("Location: " . $redirect);
        exit();
    } else {
        die("❌ Database Error: " . $conn->error);
    }
} else {
    die("❌ Error: No course data received from the button.");
}
$conn->close();
?>
