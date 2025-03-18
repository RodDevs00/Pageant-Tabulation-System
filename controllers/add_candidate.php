<?php
require '../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cno = $_POST['cno'];
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $department = $_POST['department'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $category = $_POST['category'];
    $motto = $_POST['motto'];
    $bio = $_POST['bio'];

    // Define upload directory
    $uploadDir = __DIR__ . "/../uploads/";  // Adjust based on your folder structure


    // Check if the uploads directory exists, if not, create it
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create directory with full permissions
    }

    // Handle file upload
    $photo = '';
    if (!empty($_FILES['photo']['name'])) {
        $photo = time() . '_' . basename($_FILES["photo"]["name"]);
        $target_file = $uploadDir . $photo;

        if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            echo json_encode(["status" => "error", "message" => "Failed to upload image."]);
            exit;
        }
    }

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO contestants (candidate_number,full_name, age, gender, department, course, year, category, motto, bio, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isissssssss",$cno, $full_name, $age, $gender, $department, $course, $year, $category, $motto, $bio, $photo);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Candidate added successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add candidate."]);
    }

    $stmt->close();
    $conn->close();
}
?>
