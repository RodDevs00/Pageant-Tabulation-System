<?php
require '../database/connection.php';

header("Content-Type: application/json");

$judge_id = $_GET['judge_id'] ?? null;
$candidate_id = $_GET['candidate_id'] ?? null;

if (!$judge_id || !$candidate_id) {
    echo json_encode(["error" => "Missing judge_id or candidate_id"]);
    exit;
}

$query = "SELECT criterion_id, score FROM scores WHERE judge_id = ? AND contestant_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $judge_id, $candidate_id);
$stmt->execute();
$result = $stmt->get_result();

$scores = [];
while ($row = $result->fetch_assoc()) {
    $scores[$row['criterion_id']] = $row['score'];
}

echo json_encode($scores);
?>
