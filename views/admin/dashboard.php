<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.html"); // Two levels up
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADFC Pageant Dashboard</title>

    <!-- Updated CSS paths -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Include Sidebar & Topbar -->
<?php include '../components/sidebar.php'; ?>
<?php include '../components/topbar.php'; ?>

<div class="content" id="content">
    <h2>🎉 Pageant Dashboard</h2>
    <p>Choose an action from the sidebar to manage the ADFC Pageant system.</p>
</div>

<!-- Updated JS paths -->
<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>


</body>
</html>
