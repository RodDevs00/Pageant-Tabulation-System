<?php
require '../database/connection.php';

$id = $_POST['id'];

$sql = "DELETE FROM criteria WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();
?>
