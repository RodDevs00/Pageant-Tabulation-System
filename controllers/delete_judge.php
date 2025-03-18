<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.html"); // Redirect if not logged in
    exit;
}

require '../database/connection.php'; // Ensure correct path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['judge_id'])) {
        $judge_id = $_POST['judge_id'];

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $judge_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Judge deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete judge.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    }
}

$conn->close();
?>
