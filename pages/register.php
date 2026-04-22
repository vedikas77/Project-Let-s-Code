<?php
$conn = new mysqli("localhost", "root", "", "letscode");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = trim($_POST['username']);
$email    = trim($_POST['email']);
$password = trim($_POST['password']);

// Basic validation
if ($username == "" || $email == "" || $password == "") {
    echo "<script>alert('All fields are required'); window.location='register.html';</script>";
    exit();
}

// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format'); window.location='register.html';</script>";
    exit();
}

// Password validation
if (strlen($password) < 6) {
    echo "<script>alert('Password must be at least 6 characters'); window.location='register.html';</script>";
    exit();
}
// 🔍 CHECK IF USER ALREADY EXISTS
$checkSql = "SELECT id FROM users WHERE username = ? OR email = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("ss", $username, $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    // 🚫 User already exists
    echo "<script>
            alert('User already registered. Please login.');
            window.location = 'login.html';
          </script>";
    exit();
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $hashedPassword);

if ($stmt->execute()) {
    echo "<script>
            alert('Registration successful! Please login.');
            window.location = 'login.html';
          </script>";
} else {
    echo "<script>alert('Something went wrong'); window.location='register.html';</script>";
}
$conn->close();
?>
