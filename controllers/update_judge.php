<?php
session_start();
require '../database/connection.php'; // Adjust if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if ($password) {
        $query = "UPDATE users SET username = '$username', password = '$password' WHERE id = '$id'";
    } else {
        $query = "UPDATE users SET username = '$username' WHERE id = '$id'";
    }

    if (mysqli_query($conn, $query)) {
        echo 'Judge updated successfully!';
    } else {
        echo 'Error updating judge: ' . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
