<?php
require '../database/connection.php'; 

header("Content-Type: application/json");

$query = "SELECT * FROM criteria WHERE top5 = TRUE ORDER BY id ASC"; // Select only top5 = true

$result = mysqli_query($conn, $query);

$criteria = [];
while ($row = mysqli_fetch_assoc($result)) {
    $criteria[] = $row;
}

echo json_encode($criteria);
?>