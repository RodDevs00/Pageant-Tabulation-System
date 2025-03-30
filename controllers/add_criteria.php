<?php
require '../database/connection.php';

$name = $_POST['name'];
$category = $_POST['category'];
$percentage = $_POST['percentage'];
$top5 = isset($_POST['top5']) && $_POST['top5'] === 'true' ? 1 : 0; // Retrieve and convert top5

$sql = "INSERT INTO criteria (name, category, percentage, top5) VALUES (?, ?, ?, ?)"; // Add top5 column
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $name, $category, $percentage, $top5); // Bind top5

if ($stmt->execute()) {
    echo "Criteria added successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>