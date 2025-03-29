<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.html");
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "adfc_pageant");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get category filter
$category = isset($_GET['category']) ? $_GET['category'] : 'Miss ADFC';

// Fetch top 5 candidates
$query = "
    SELECT 
        c.id AS candidate_id,
        c.full_name,
        c.candidate_number,
        c.category,
        c.photo,
        ROUND(AVG(s.score), 2) AS weighted_average
    FROM scores s
    JOIN contestants c ON s.contestant_id = c.id
    WHERE c.category = ?
    GROUP BY c.id
    ORDER BY weighted_average DESC
    LIMIT 5";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

$candidates = [];
while ($row = $result->fetch_assoc()) {
    $candidates[] = $row;
}

$mysqli->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 5 Candidates - <?= htmlspecialchars($category); ?></title>
    
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f8f9fa;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .candidate {
            display: flex;
            align-items: center;
            background: #ffffff;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            justify-content: space-between;
        }
        .candidate img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }
        .candidate-info {
            text-align: left;
            flex-grow: 1;
        }
        .candidate:hover {
            background: #e9ecef;
        }
        .rank {
            font-size: 18px;
            font-weight: bold;
            margin-right: 15px;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            font-size: 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<nav class="navbar topbar navbar-light px-3 d-flex align-items-center">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../../assets/image/adflogo.png" alt="Logo" class="logo me-2">
    <a href="rate.php" class="btn top5-btn">
        <i class="fas fa-home"></i> Back to homepage
    </a>
    </a>

    <!-- Logout Link (Pushed to the Right) -->
    <div class="ms-auto">
        <a href="../../auth/logout.php" class="logout-link">
            <i class="fas fa-sign-out-alt"></i> 
        </a>
    </div>
</nav>

<style>
    .topbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: white;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    padding: 10px 15px;
    }
    .container {
        margin-top: 80px; /* Adjust to prevent content from hiding under the topbar */
    }

    .top5-btn {
        background: #007bff; /* Blue */
        color: white;
        font-weight: bold;
        border-radius: 20px;
        padding: 8px 15px;
        transition: 0.3s ease-in-out;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        text-decoration: none;
    }

    .top5-btn:hover {
        background: #0056b3; /* Darker Blue */
        color: white;
        transform: scale(1.05);
    }

    .top5-btn i {
        margin-right: 5px;
    }

    .logo {
        height: 40px; /* Adjust size as needed */
    }

    .instruction {
            font-size: 16px;
            color: #555;
            margin-bottom: 15px;
        }
</style>



<div class="container mt-4">
    <h2>Top 5 Candidates - <?= htmlspecialchars($category); ?></h2>
    
    <!-- Dropdown Filter -->
    <form method="GET" action="top5.php" class="text-center mb-4">
        <label for="category"><strong>Select Category:</strong></label>
        <select name="category" id="category" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
            <option value="Miss ADFC" <?= ($category == 'Miss ADFC') ? 'selected' : ''; ?>>Miss ADFC</option>
            <option value="Mister ADFC" <?= ($category == 'Mister ADFC') ? 'selected' : ''; ?>>Mister ADFC</option>
        </select>
    </form>
    <div class="d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <i class="fas fa-trophy me-2" style="color: gold; font-size: 24px;"></i> 
        <h2 class="m-0">Top 5 <?= htmlspecialchars($category); ?></h2>
    </div>
    <p class="instruction m-0"><strong>Click a candidate to rate</strong></p>
</div>


    <div>
        <?php if (!empty($candidates)): ?>
            <?php $rank = 1; ?>
            <?php foreach ($candidates as $candidate): ?>
                <div class="candidate">
                    <span class="rank">#<?= $rank++; ?></span>
                    <img src="../../uploads/<?= !empty($candidate['photo']) ? htmlspecialchars($candidate['photo']) : 'default.jpg'; ?>" alt="Candidate">
                    <div class="candidate-info">
                        <strong><?= htmlspecialchars($candidate['full_name']); ?></strong><br>
                        Candidate #<?= htmlspecialchars($candidate['candidate_number']); ?>
                    </div>
                    <span>Avg Score: <strong><?= $candidate['weighted_average']; ?></strong></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">No candidates found for <?= htmlspecialchars($category); ?>.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Scripts -->
<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>
