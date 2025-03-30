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
    <link rel="stylesheet" href="../../assets/css/top5.css">
    
    <style>
        /* Optional: Add some spacing and styling for the criteria */
        #criteriaContainer .mb-3 {
            margin-bottom: 15px;
        }

        #criteriaContainer label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        #criteriaContainer .input-group {
            display: flex; /* Use flexbox for better control */
            align-items: center; /* Align items vertically */
        }

        #criteriaContainer .input-group .btn-success {
            white-space: nowrap;
            padding: 0.375rem 0.75rem;
            flex-shrink: 0; /* Prevent the button from shrinking */
            width: auto; /* Allow the button to take its natural width */
        }

        #criteriaContainer .input-group input[type="number"] {
            flex-grow: 1; /* Allow the input to take up remaining space */
        }
    </style>
</head>
<body>
<nav class="navbar topbar navbar-light px-3 d-flex align-items-center">
    <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../../assets/image/adflogo.png" alt="Logo" class="logo me-2">
    <a href="rate.php" class="btn top5-btn">
        <i class="fas fa-home"></i> Back to homepage
    </a>
    </a>

    <div class="ms-auto">
        <a href="../../auth/logout.php" class="logout-link">
            <i class="fas fa-sign-out-alt"></i> 
        </a>
    </div>
</nav>

<div class="container mt-4">
    <h2>Top 5 Candidates - <?= htmlspecialchars($category); ?></h2>
    
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

<span class="d-flex align-items-center justify-content-start my-3">In no particular order</span>
    <div>
        <?php if (!empty($candidates)): ?>
            <?php $rank = 1; ?>
            <?php foreach ($candidates as $candidate): ?>
                <div class="candidate" data-candidate-id="<?= htmlspecialchars($candidate['candidate_id']); ?>" data-candidate-name="<?= htmlspecialchars($candidate['full_name']); ?>" data-candidate-number="<?= htmlspecialchars($candidate['candidate_number']); ?>" data-candidate-category="<?= htmlspecialchars($candidate['category']); ?>">
                    <img src="../../uploads/<?= !empty($candidate['photo']) ? htmlspecialchars($candidate['photo']) : 'default.jpg'; ?>" alt="Candidate">
                    <div class="candidate-info">
                        <strong><?= htmlspecialchars($candidate['full_name']); ?></strong><br>
                    </div>
                    <span>Candidate #<?= htmlspecialchars($candidate['candidate_number']); ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">No candidates found for <?= htmlspecialchars($category); ?>.</p>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="rateModal" tabindex="-1" aria-labelledby="rateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" id="judge_id" value="<?php echo $_SESSION['user_id']; ?>">
                <h5 class="modal-title" id="rateModalLabel">Rate Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rateForm">
                    <input type="hidden" id="candidate_id">
                    
                    <p class="fw-bold" style="font-size: 1.5rem; margin-bottom: 5px;">
                        <span id="candidate_name"></span> 
                        <span id="candidate_number" class="text-muted"></span>
                    </p>

                    <p id="candidate_category" class="fw-bold text-center p-2 rounded" 
                        style="background: linear-gradient(to right, rgb(57, 17, 203), #2575fc); 
                                color: white; font-size: 1.2rem; padding: 8px 20px; margin-bottom: 20px;">
                    </p>

                    <div id="criteriaContainer"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openRateModal(candidate) {
        document.getElementById("candidate_id").value = candidate.id;
        document.getElementById("candidate_name").innerText = candidate.full_name;
        document.getElementById("candidate_number").innerText = `(${candidate.candidate_number})`;
        document.getElementById("candidate_category").innerText = candidate.category;

        fetch("../../controllers/get_top_criteria.php")
            .then(response => response.json())
            .then(criteria => {
                fetch(`../../controllers/get_scores.php?judge_id=${document.getElementById("judge_id").value}&candidate_id=${candidate.id}`)
                    .then(response => response.json())
                    .then(scores => {
                        let container = document.getElementById("criteriaContainer");
                        container.innerHTML = ""; // Clear previous data

                        let filteredCriteria = criteria.filter(crit => crit.category === candidate.category);
                        let foundFirstUnrated = false;

                        if (filteredCriteria.length === 0) {
                            container.innerHTML = "<p class='text-muted'>No criteria available for this category.</p>";
                        } else {
                            filteredCriteria.forEach(crit => {
                                let previousScore = scores[crit.id] || "";
                                let isRated = previousScore !== "";
                                let isEditable = !isRated && !foundFirstUnrated;

                                if (!isRated) foundFirstUnrated = true;

                                let html = `
                                    <div class="mb-3">
                                        <label for="criteria_${crit.id}" class="form-label">${crit.name} (${crit.percentage}%)</label>
                                        <div class="input-group">
                                            <input type="number" id="criteria_${crit.id}" class="form-control" min="50" max="100" step="1"
                                                value="${previousScore}" ${isRated ? "disabled" : isEditable ? "" : "disabled"}>
                                            <button class="btn btn-success submit-criteria" data-criteria-id="${crit.id}" 
                                                ${isRated ? "disabled" : isEditable ? "" : "disabled"}>${isRated ? "✅" : "✔️ Rate"}</button>
                                        </div>
                                    </div>
                                `;
                                container.innerHTML += html;
                            });
                        }

                        $("#rateModal").modal("show");
                    })
                    .catch(error => {
                        console.error("Error fetching scores:", error);
                        Swal.fire({
                            title: "Error",
                            text: "Failed to load previous scores. Please try again.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    });
            })
            .catch(error => {
                console.error("Error fetching criteria:", error);
                Swal.fire({
                    title: "Error",
                    text: "Failed to load criteria. Please try again.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            });
    }

    document.getElementById("criteriaContainer").addEventListener("click", function (event) {
        if (!event.target.classList.contains("submit-criteria")) return;

        let btn = event.target;
        let input = btn.previousElementSibling;
        let criteriaId = btn.getAttribute("data-criteria-id");
        let candidateId = document.getElementById("candidate_id").value;
        let judgeId = document.getElementById("judge_id").value;
        let category = document.getElementById("candidate_category").innerText.trim();
        let score = input.value.trim();

        if (!score || isNaN(score) || score < 50 || score > 100) {
        Swal.fire({
            title: "Invalid Score",
            text: "Each score must be between 50 and 100.",
            icon: "warning",
            confirmButtonText: "OK"
        });
        return;
    }

        input.disabled = true;
        btn.disabled = true;

        $.ajax({
            url: "../../controllers/submit_rating.php",
            type: "POST",
            data: {
                judge_id: judgeId,
                candidate_id: candidateId,
                category: category,
                ratings: JSON.stringify({ [criteriaId]: parseInt(score) })
            },
            success: function(response) {
                try {
                    let res = typeof response === "string" ? JSON.parse(response) : response;

                    if (res.success) {
                        btn.innerHTML = "✅";

                        let nextGroup = input.closest(".mb-3").nextElementSibling;
                        if (nextGroup) {
                            let nextInput = nextGroup.querySelector("input");
                            let nextSubmitBtn = nextGroup.querySelector(".submit-criteria");
                            if (nextInput) nextInput.disabled = false;
                            if (nextSubmitBtn) nextSubmitBtn.disabled = false;
                        }
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: res.message,
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                        input.disabled = false;
                        btn.disabled = false;
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", response);
                    Swal.fire({
                        title: "Unexpected Error",
                        text: "An unexpected error occurred.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                    input.disabled = false;
                    btn.disabled = false;
                }
            },
            error: function(xhr, status, error) {
                console.error("Error submitting rating:", error);
                Swal.fire({
                    title: "Submission Failed",
                    text: "Failed to submit rating. Please try again.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                input.disabled = false;
                btn.disabled = false;
            }
        });
    });

    $(document).ready(function() {
        $('.candidate').click(function() {
            let candidate = {
                id: $(this).data('candidate-id'),
                full_name: $(this).data('candidate-name'),
                candidate_number: $(this).data('candidate-number'),
                category: $(this).data('candidate-category')
            };
            openRateModal(candidate);
        });
    });
</script>

</body>
</html>