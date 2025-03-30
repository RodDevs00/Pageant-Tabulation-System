<?php
session_start();
require '../database/connection.php';// Adjust the path to your database connection file

// Get the current ADFC edition
$adfc_edition = "adfc " . date("Y");

// Fetch the current status
$query = "SELECT top5_enabled FROM top5toggle WHERE adfc_edition = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $adfc_edition);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $new_status = !$row['top5_enabled']; // Toggle the value

    // Update the database
    $updateQuery = "UPDATE top5toggle SET top5_enabled = ? WHERE adfc_edition = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("is", $new_status, $adfc_edition);
    $updateStmt->execute();

    echo json_encode(["success" => true, "new_status" => $new_status]);
} else {
    echo json_encode(["success" => false, "message" => "Record not found"]);
}

$conn->close();
?>
