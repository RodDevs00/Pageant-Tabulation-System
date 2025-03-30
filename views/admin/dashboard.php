<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.html");
    exit;
}

require '../../database/connection.php';

$category = isset($_GET['category']) ? $_GET['category'] : 'Miss ADFC';



// Get the current ADFC edition
$adfc_edition = "adfc " . date("Y");

// Fetch the toggle status
$query = "SELECT top5_enabled FROM top5toggle WHERE adfc_edition = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $adfc_edition);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$top5_enabled = $row ? $row['top5_enabled'] : false;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADFC Pageant Dashboard</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">

    <style>
        .candidate-card {
            padding: 10px;
            border-radius: 10px;
            background: #f8f9fa;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .candidate-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
        .rank-badge {
            font-size: 14px;
            font-weight: bold;
            margin-right: 10px;
        }
        .top-candidate {
            background: #ffeb3b !important;
            border: 2px solid #ffc107;
        }
        .candidate-info {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }
        .score {
            font-weight: bold;
        }
    </style>
    <style>
/* Ensure filter and toggle are on separate lines */
/* Ensure everything stays in one row */
.filter-toggle-wrapper {
    display: flex;
    gap: 20px;
    justify-content: flex-start; /* Aligns everything to the left */
}

/* Smaller Switch */
.switch {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 20px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: gray;
    transition: 0.3s;
    border-radius: 10px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 14px;
    width: 14px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: blue;
}

input:checked + .slider:before {
    transform: translateX(20px);
}

.toggle-label {
    font-size: 14px;
    font-weight: bold;
    color: #333;
}
</style>
</head>
<body>

<?php include '../components/sidebar.php'; ?>
<?php include '../components/topbar.php'; ?>

<div class="content" id="content">
    
    <h2>Pageant Dashboard</h2>
<!-- Filter and Toggle Container -->
<div class="filter-toggle-wrapper d-flex align-items-center gap-4 my-3">
    
    <!-- Category Filter -->
    <div class="filter-container d-flex align-items-center gap-2">
        <label for="categoryFilter"><strong>Filter by Category:</strong></label>
        <select id="categoryFilter" class="form-select w-auto">
            <option value="Miss ADFC" <?= ($category == 'Miss ADFC') ? 'selected' : ''; ?>>Miss ADFC</option>
            <option value="Mister ADFC" <?= ($category == 'Mister ADFC') ? 'selected' : ''; ?>>Mister ADFC</option>
        </select>
    </div>

    <!-- Toggle Button -->
    <div class="toggle-container d-flex align-items-center gap-2">
        <label class="switch">
            <input type="checkbox" id="top5Toggle" <?= $top5_enabled ? 'checked' : '' ?>>
            <span class="slider"></span>
        </label>
        <span class="toggle-label">Activate Top 5 in Judges</span>
    </div>

</div>
    <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">LIVE - Candidate Rankings - <span id="categoryTitle"><?= htmlspecialchars($category); ?></span></h5>
            <div>
            <button id="printWinners" class="btn btn-warning btn-sm mx-4">🖨 Print Winners</button>
            <button id="printResults" class="btn btn-warning btn-sm">🖨 Print Top 5</button>
            </div>
            
        </div>
        <div class="card-body">
            <div id="topCandidateContainer"></div>
            <div id="candidateList">
                <p class="text-muted">Loading candidates...</p>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>

<script>
    function fetchCandidates() {
        let selectedCategory = document.getElementById('categoryFilter').value;
        $("#categoryTitle").text(selectedCategory);

        $.ajax({
            url: '../../controllers/fetch_candidates.php',
            type: 'GET',
            data: { category: selectedCategory },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    console.error(response.error);
                    return;
                }

                let candidateList = $("#candidateList");
                let topCandidateContainer = $("#topCandidateContainer");
                candidateList.empty();
                topCandidateContainer.empty();

                // Display Top Candidate
                if (response.topCandidate) {
                    let topHtml = `
                        <div class="candidate-card top-candidate">
                            <span class="rank-badge">🏆 #1</span>
                            <img src="${response.topCandidate.image}" class="candidate-img" alt="Candidate Image">
                            <div class="candidate-info">
                                <strong>${response.topCandidate.full_name}</strong> (Candidate #${response.topCandidate.candidate_number})
                            </div>
                            <span class="score">Avg Score: <strong>${response.topCandidate.weighted_average}</strong></span>
                        </div>`;
                    topCandidateContainer.html(topHtml);
                }

                // Display Other Candidates
                let rank = 2;
                response.candidates.forEach(candidate => {
                    candidateList.append(`
                        <div class="candidate-card">
                            <span class="rank-badge">#${rank++}</span>
                            <img src="${candidate.image}" class="candidate-img" alt="Candidate Image">
                            <div class="candidate-info">
                                <strong>${candidate.full_name}</strong> (Candidate #${candidate.candidate_number})
                            </div>
                            <span class="score">Avg Score: <strong>${candidate.weighted_average}</strong></span>
                        </div>
                    `);
                });
            },
            error: function() {
                console.error("Failed to fetch data.");
            }
        });
    }

    setInterval(fetchCandidates, 5000);
    document.addEventListener("DOMContentLoaded", fetchCandidates);
    $("#categoryFilter").on("change", fetchCandidates);
</script>
<script>
    document.getElementById("printResults").addEventListener("click", function() {
    let selectedCategory = document.getElementById("categoryFilter").value;

    $.ajax({
        url: '../../controllers/fetch_candidates.php',
        type: 'GET',
        data: { category: selectedCategory },
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                console.error(response.error);
                return;
            }

            let topCandidates = response.candidates.slice(0, 5); // Get top 5 (1 already displayed separately)
            if (response.topCandidate) {
                topCandidates.unshift(response.topCandidate); // Include the #1 rank
            }

            if (topCandidates.length === 0) {
                alert("No candidates found for printing.");
                return;
            }

            // Generate print content
            let printContent = `
               <html>
        <head>
            <title>Top 5 Candidates - ${selectedCategory}</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; }
                h2 { color: #333; }
                .logo { width: 100px; display: block; margin: 0 auto; }
                .candidate { margin: 10px 0; padding: 10px; border: 1px solid #ccc; display: flex; align-items: center; }
                .candidate img { width: 50px; height: 50px; border-radius: 50%; margin-right: 10px; }
                .candidate-info { text-align: left; }
                .rank { font-size: 20px; font-weight: bold; margin-right: 10px; }
            </style>
        </head>
        <body>
            <img src="../../assets/image/adflogo.png" alt="ADFC Logo" class="logo">
            <h2>Top 5 Candidates - ${selectedCategory}</h2>
            <p>In no particular order</>
            `;

            topCandidates.forEach((candidate, index) => {
                printContent += `
                    <div class="candidate">
                        <span class="rank">#${index + 1}</span>
                        <img src="${candidate.image}" alt="${candidate.photo}">
                        <div class="candidate-info">
                            <strong>${candidate.full_name}</strong> (Candidate #${candidate.candidate_number})<br>
                            Avg Score: <strong>${candidate.weighted_average}</strong>
                        </div>
                    </div>
                `;
            });

            printContent += `</body></html>`;

            // Open print preview
            let printWindow = window.open("", "", "width=600,height=700");
            printWindow.document.open();
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
        },
        error: function() {
            console.error("Failed to fetch data.");
        }
    });
});

</script>
<script>
  document.getElementById("printWinners").addEventListener("click", function() {
    let selectedCategory = document.getElementById("categoryFilter").value;

    $.ajax({
        url: '../../controllers/fetch_candidates.php',
        type: 'GET',
        data: { category: selectedCategory },
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                console.error(response.error);
                return;
            }

            let topCandidates = response.candidates.slice(0, 5); // Get top 5 (1 already displayed separately)
            if (response.topCandidate) {
                topCandidates.unshift(response.topCandidate); // Include the #1 rank
            }

            if (topCandidates.length === 0) {
                alert("No candidates found for printing.");
                return;
            }

            let printContent = `
                <html>
                    <head>
                        <title>Winners - ${selectedCategory}</title>
                        <style>
                            body { font-family: Arial, sans-serif; text-align: center; }
                            h2 { color: #333; }
                            .logo { width: 100px; display: block; margin: 0 auto; }
                            .candidate { margin: 10px 0; padding: 10px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: space-between; }
                            .candidate img { width: 50px; height: 50px; border-radius: 50%; margin-right: 10px; }
                            .candidate-info { text-align: left; flex-grow: 1; } /* Allow info to grow */
                            .rank { font-size: 20px; font-weight: bold; text-align: right; align-self: center; min-width: 120px; } /* Set min-width for rank */
                            .candidate-name { text-align: left; }
                        </style>
                    </head>
                    <body>
                        <img src="../../assets/image/adflogo.png" alt="ADFC Logo" class="logo">
                        <h2>Winners - ${selectedCategory}</h2>
            `;

            const ranks = selectedCategory.toLowerCase() === 'miss adfc' ? 
                ["Miss ADFC", "1st Runner Up", "2nd Runner Up", "3rd Runner Up", "4th Runner Up"] :
                ["Mister ADFC", "1st Runner Up", "2nd Runner Up", "3rd Runner Up", "4th Runner Up"];

            topCandidates.forEach((candidate, index) => {
                printContent += `
                    <div class="candidate">
                        <img src="${candidate.image}" alt="${candidate.photo}">
                        <div class="candidate-info">
                            <div class="candidate-name"> 
                                <strong>${candidate.full_name}</strong> (Candidate #${candidate.candidate_number}) 
                            </div>
                            Avg Score: <strong>${candidate.weighted_average}</strong>
                        </div>
                        <span class="rank">${ranks[index]}</span>
                    </div>
                `;
            });

            printContent += `</body></html>`;

            let printWindow = window.open("", "", "width=600,height=700");
            printWindow.document.open();
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
        },
        error: function() {
            console.error("Failed to fetch data.");
        }
    });
});
</script>
<script>
   $(document).ready(function () {
    $('#top5Toggle').change(function () {
        $.post('../../controllers/toggle_top5.php', function(response) {
            let data = JSON.parse(response);
            if (!data.success) {
                alert("Error updating Top 5 status.");
            }
        });
    });
});
</script>

</body>
</html>
