<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.html"); // Redirect if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results | ADFC Pageant System</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/mystyle.css"> 
</head>
<body>

<!-- Include Sidebar & Topbar -->
<?php include '../components/sidebar.php'; ?>
<?php include '../components/topbar.php'; ?>

<div class="content" id="content">
    <h3>Results</h3>


<!-- Bootstrap and jQuery JS -->
<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/sweetalert2.min.js"></script>

</body>
</html>
