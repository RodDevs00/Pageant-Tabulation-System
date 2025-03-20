<?php
require '../database/connection.php';

$id = $_POST['id'];
$name = $_POST['name'];
$percentage = $_POST['percentage'];
$category = $_POST['category']; // Ensure category is fetched from POST

$sql = "UPDATE criteria SET name = ?, percentage = ?, category = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sisi", $name, $percentage, $category, $id);
$stmt->execute();
$stmt->close();
$conn->close();
?>
