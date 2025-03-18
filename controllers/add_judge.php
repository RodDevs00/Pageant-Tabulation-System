<?php
session_start();
require '../database/connection.php'; // Adjust if needed

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Simple hashing, switch to password_hash() for better security
    $role = 'judge';

    // Check if username already exists
    $checkQuery = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo 'Username already exists!';
        exit;
    }

    // Insert new judge
    $insertQuery = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param('sss', $username, $password, $role);

    if ($stmt->execute()) {
        echo 'Judge added successfully!';
    } else {
        echo 'Failed to add judge.';
    }

    $stmt->close();
    $conn->close();
}
?>
