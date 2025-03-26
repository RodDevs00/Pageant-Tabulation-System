<?php
session_start();
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User is not logged in."]);
    exit;
}

if (!isset($_POST['judge_id'], $_POST['candidate_id'], $_POST['ratings'], $_POST['category'])) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit;
}

$judge_id = intval($_POST['judge_id']);
$candidate_id = intval($_POST['candidate_id']);
$category = trim($_POST['category']); // ✅ Use the actual category from AJAX
$ratingsJson = $_POST['ratings'];

$ratings = json_decode($ratingsJson, true);
if (!is_array($ratings)) {
    echo json_encode(["success" => false, "message" => "Invalid ratings format."]);
    exit;
}

require '../database/connection.php';

try {
    // ✅ Update the check query to include category
    $stmt_check = $conn->prepare("SELECT criterion_id FROM scores WHERE judge_id = ? AND contestant_id = ? AND criterion_id = ? AND category = ?");
    $stmt_insert = $conn->prepare("INSERT INTO scores (judge_id, contestant_id, criterion_id, category, score) VALUES (?, ?, ?, ?, ?)");

    foreach ($ratings as $criterion_id => $score) {
        // ✅ Bind parameters including category
        $stmt_check->bind_param("iiis", $judge_id, $candidate_id, $criterion_id, $category);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "You have already rated this candidate in this category."]);
            exit;
        }

        // ✅ Now properly inserting the correct category value
        $stmt_insert->bind_param("iiisi", $judge_id, $candidate_id, $criterion_id, $category, $score);
        $stmt_insert->execute();
    }

    echo json_encode(["success" => true, "message" => "Ratings submitted successfully."]);
} catch (Exception $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Database error."]);
}
?>
