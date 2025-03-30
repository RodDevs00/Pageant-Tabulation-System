<?php
require '../database/connection.php';

$id = $_POST['id'];
$name = $_POST['name'];
$percentage = $_POST['percentage'];
$category = $_POST['category'];
$top5 = isset($_POST['top5']) && $_POST['top5'] === 'true' ? 1 : 0; // Retrieve and convert top5

$sql = "UPDATE criteria SET name = ?, percentage = ?, category = ?, top5 = ? WHERE id = ?"; // Add top5 column
$stmt = $conn->prepare($sql);
$stmt->bind_param("siiii", $name, $percentage, $category, $top5, $id); // Bind top5

if ($stmt->execute()) {
    echo "Criteria updated successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>