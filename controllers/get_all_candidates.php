<?php
require '../database/connection.php';

$query = "SELECT * FROM contestants"; 
$result = $conn->query($query);

$candidates = [];
while ($row = $result->fetch_assoc()) {
    $candidates[] = $row;
}

echo json_encode($candidates);
?>
