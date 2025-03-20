<?php
require '../database/connection.php';

$name = $_POST['name'];
$category = $_POST['category']; // Fetch category from POST
$percentage = $_POST['percentage'];

$sql = "INSERT INTO criteria (name, category, percentage) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $name, $category, $percentage);
$stmt->execute();
$stmt->close();
$conn->close();
?>
