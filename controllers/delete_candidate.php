<?php
require '../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Get photo filename to delete it from the server
    $stmt = $conn->prepare("SELECT photo FROM contestants WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($photo);
    $stmt->fetch();
    $stmt->close();

    // Delete candidate from database
    $stmt = $conn->prepare("DELETE FROM contestants WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Delete photo file if it exists
        if (!empty($photo) && file_exists("../../uploads/" . $photo)) {
            unlink("../../uploads/" . $photo);
        }
        echo json_encode(["status" => "success", "message" => "Candidate deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete candidate."]);
    }

    $stmt->close();
    $conn->close();
}
?>
