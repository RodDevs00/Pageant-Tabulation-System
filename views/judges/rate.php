<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.html");
    exit;
}

require '../../database/connection.php';

// Search filter
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Search condition
$whereClause = !empty($search) ? "AND full_name LIKE '%$search%'" : "";

// Query for Mister ADFC
$queryMister = "SELECT * FROM contestants WHERE category = 'MISTER ADFC' $whereClause ORDER BY candidate_number ASC";
$resultMister = mysqli_query($conn, $queryMister);

// Query for Miss ADFC
$queryMiss = "SELECT * FROM contestants WHERE category = 'MISS ADFC' $whereClause ORDER BY candidate_number ASC";
$resultMiss = mysqli_query($conn, $queryMiss);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADFC Pageant Dashboard</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/rate.css">

    <style>
       

    </style>
</head>
<body>
<nav class="navbar topbar navbar-light px-3 d-flex align-items-center">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../../assets/image/adflogo.png" alt="Logo" class="logo me-2">
        
        <!-- Styled Top 5 Button -->
        <a href="top5.php" class="btn top5-btn">
            <i class="fas fa-trophy"></i>Proceed to Top 5
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
</style>



<div class="content" id="content">


     <!-- Featured Image Section with Card -->
     <div class="container my-1 pt-5">
        <div class="card shadow">
            <div class="card-img-container">
                <img src="../../assets/image/adfbackdrop.jpg" alt="Logo">
            </div>
        </div>
    </div>
    <div class="text-center my-4">
        <p class="scroll-instruction">Scroll to vote</p>
        <div class="scroll-chevron">
            <i class="fa fa-chevron-down"></i>
        </div>
    </div>

    <h2 class="rating-title">This year's Candidates</h2>

    <!-- MISTER ADFC Row -->
    <div class="category-container">
        <div class="category-title ms-3">Mister ADFC</div>
        <div class="scroll-container">
            <?php if (mysqli_num_rows($resultMister) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($resultMister)): ?>
                    <?php
                    $photo = !empty($row['photo']) ? "../../uploads/{$row['photo']}" : "../../assets/img/default-avatar.png";
                    $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    ?>
                    <div class='candidate-card'>
                        <img src='<?= $photo ?>' alt='Profile Picture'>

                        <div class='position-absolute top-0 end-0 mt-2 me-2'>
                            <button class='btn btn-primary btn-sm' onclick='openRateModal(<?= $rowData ?>)'>
                                <i class='fas fa-star'></i> Rate
                            </button>
                        </div>

                        <div class='info-overlay'>
                            <h5 class='fw-bold m-0'><?= $row['full_name'] ?> <span class='fs-6 fw-bold text-light'></span></h5>
                            <p class="fw-bold text-center  rounded" style="background: linear-gradient(to right,rgb(34, 18, 171), #2575fc); color: white; font-size: 1rem; display: inline-block; padding: 2px 10px;"><?= $row['category'] ?> <span class='fs-6 fw-bold text-light'>(<?= $row['candidate_number'] ?>)</p>
                            <p class='m-0 small'>Age: <?= $row['age'] ?> | Dept: <?= $row['department'] ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class='text-danger fw-bold'>No candidates found</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- MISS ADFC Row -->
    <div class="category-container">
    <div class="category-title ms-3 fs-4 fw-bold text-dark" style="font-family: 'Poppins', sans-serif;">Miss ADFC</div>

        <div class="scroll-container">
            <?php if (mysqli_num_rows($resultMiss) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($resultMiss)): ?>
                    <?php
                    $photo = !empty($row['photo']) ? "../../uploads/{$row['photo']}" : "../../assets/img/default-avatar.png";
                    $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    ?>
                    <div class='candidate-card'>
                        <img src='<?= $photo ?>' alt='Profile Picture'>

                        <div class='position-absolute top-0 end-0 mt-2 me-2'>
                            <button class='btn btn-primary btn-sm' onclick='openRateModal(<?= $rowData ?>)'>
                                <i class='fas fa-star'></i> Rate
                            </button>
                        </div>

                        <div class='info-overlay'>
                            <h5 class='fw-bold m-0'><?= $row['full_name'] ?> </span></h5>
                            <p class="fw-bold text-center  rounded" style="background: linear-gradient(to right,rgb(17, 29, 203), #2575fc); color: white; font-size: 1rem; display: inline-block; padding: 2px 10px;"><?= $row['category'] ?> <span class='fs-6 fw-bold text-light'>(<?= $row['candidate_number'] ?>)</p>
                            <p class='m-0 small'>Age: <?= $row['age'] ?> | Dept: <?= $row['department'] ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class='text-danger fw-bold'>No candidates found</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- RATE MODAL -->
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
                    
                    <p class="fw-bold" style="font-size: 1.5rem;">
                        <span id="candidate_name"></span> 
                        <span id="candidate_number" class="text-muted"></span>
                    </p>

                    <p id="candidate_category" class="fw-bold text-center p-2 rounded" 
                       style="background: linear-gradient(to right, rgb(57, 17, 203), #2575fc); 
                              color: white; font-size: 1.2rem;  padding: 8px 20px;">
                    </p>

                    <!-- Criteria will be loaded dynamically -->
                    <div id="criteriaContainer"></div>
                </form>
            </div>
        </div>
    </div>
</div>




<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/sweetalert2.min.js"></script>
<script src="../../assets/js/rate.js"></script>

<script>
// RATE MODAL
function openRateModal(candidate) {
    document.getElementById("candidate_id").value = candidate.id;
    document.getElementById("candidate_name").innerText = candidate.full_name;
    document.getElementById("candidate_number").innerText = `(${candidate.candidate_number})`;
    document.getElementById("candidate_category").innerText = candidate.category;

    fetch("../../controllers/get_criteria.php")
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
                            let previousScore = scores[crit.id] || ""; // Retrieve previous score if available
                            let isRated = previousScore !== ""; // Check if score exists
                            let isEditable = !isRated && !foundFirstUnrated; // Only allow first unrated input
                            
                            if (!isRated) foundFirstUnrated = true; // Mark the first unrated one as editable

                            let html = `
                                <div class="mb-3">
                                    <label for="criteria_${crit.id}" class="form-label">${crit.name} (${crit.percentage}%)</label>
                                    <div class="input-group">
                                        <input type="number" id="criteria_${crit.id}" class="form-control" min="1" max="100" step="1"
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



// INDIVIDUAL CRITERIA SUBMISSION
document.getElementById("criteriaContainer").addEventListener("click", function (event) {
    if (!event.target.classList.contains("submit-criteria")) return; // Ensure it's the correct button

    let btn = event.target;
    let input = btn.previousElementSibling;
    let criteriaId = btn.getAttribute("data-criteria-id");
    let candidateId = document.getElementById("candidate_id").value;
    let judgeId = document.getElementById("judge_id").value;
    let category = document.getElementById("candidate_category").innerText.trim();
    let score = input.value.trim();

    if (!score || isNaN(score) || score < 1 || score > 100) {
        Swal.fire({
            title: "Invalid Score",
            text: "Each score must be between 1 and 100.",
            icon: "warning",
            confirmButtonText: "OK"
        });
        return;
    }

    // Disable input and submit button
    input.disabled = true;
    btn.disabled = true;

    $.ajax({
        url: "../../controllers/submit_rating.php",
        type: "POST",
        data: {
            judge_id: judgeId,
            candidate_id: candidateId,
            category: category,    
            ratings: JSON.stringify({ [criteriaId]: parseInt(score) }) // Send only the current rating
        },
        success: function(response) {
            try {
                let res = typeof response === "string" ? JSON.parse(response) : response;

                if (res.success) {
                    btn.innerHTML = "✅"; // Indicate submission success

                    // Unlock the next input
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
                    input.disabled = false; // Re-enable on error
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




</script>

</body>
</html>
