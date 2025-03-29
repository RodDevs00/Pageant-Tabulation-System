<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "adfc_pageant");
if ($mysqli->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $mysqli->connect_error]));
}

$category = isset($_GET['category']) ? $_GET['category'] : 'Miss ADFC';

$query = "
    SELECT 
        c.id AS candidate_id,
        c.full_name,
        c.candidate_number,
        c.category,
        c.photo,  -- Fetch the actual photo filename
        ROUND(AVG(s.score), 2) AS weighted_average
    FROM scores s
    JOIN contestants c ON s.contestant_id = c.id
    WHERE c.category = ?
    GROUP BY c.id
    ORDER BY weighted_average DESC";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

$candidates = [];
while ($row = $result->fetch_assoc()) {
    // Use the exact filename from the database
    $image_path = "../../uploads/" . $row['photo'];  

    // Store image path in the array
    $row['image'] = $image_path;
    $candidates[] = $row;
}

$topCandidate = !empty($candidates) ? array_shift($candidates) : null;

echo json_encode([
    "topCandidate" => $topCandidate,
    "candidates" => $candidates
]);

$mysqli->close();
?>
