<?php
session_start();
require '../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $editcno = mysqli_real_escape_string($conn, $_POST['editcno']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $motto = mysqli_real_escape_string($conn, $_POST['motto']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);

    // Check if a new photo is uploaded
    if (!empty($_FILES['photo']['name'])) {
        $photo_name = time() . '_' . basename($_FILES['photo']['name']);
        $target_path = __DIR__ . '/../uploads/' . $photo_name;

        // Validate image type
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($_FILES['photo']['type'], $allowed_types)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid image type']);
            exit;
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
            // Update query with photo
            $query = "UPDATE contestants SET 
                      candidate_number='$editcno', 
                      full_name='$full_name', 
                      age='$age', 
                      gender='$gender', 
                      department='$department', 
                      course='$course', 
                      year='$year', 
                      category='$category', 
                      motto='$motto', 
                      bio='$bio', 
                      photo='$photo_name' 
                      WHERE id='$id'";
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
            exit;
        }
    } else {
        // Update query without photo
        $query = "UPDATE contestants SET 
                  candidate_number='$editcno',
                  full_name='$full_name', 
                  age='$age', 
                  gender='$gender', 
                  department='$department', 
                  course='$course', 
                  year='$year', 
                  category='$category', 
                  motto='$motto', 
                  bio='$bio' 
                  WHERE id='$id'";
    }

    // Execute query and handle response
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Candidate updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update candidate: ' . mysqli_error($conn)]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
