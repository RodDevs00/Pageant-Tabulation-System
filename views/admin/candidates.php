<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidates | ADFC Pageant System</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css"> 

</head>
<body>

<!-- Include Sidebar & Topbar -->
<?php include '../components/sidebar.php'; ?>
<?php include '../components/topbar.php'; ?>

<div class="content" id="content">
    <h3>Candidates Management</h3>

    <!-- Add Candidate Button -->
    <div class="d-flex justify-content-end">
    <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addCandidateModal">
        <i class="fas fa-user-plus"></i> Add Candidate
    </button>
    </div>
   

    <div class="d-flex align-items-center mb-3">
    <input type="text" id="searchInput" class="form-control w-25" placeholder="Search..." oninput="searchCandidates()">

</div>


<!-- No Match Found Message -->
<p id="noMatchMessage" class="text-center text-danger fw-bold" style="display: none;">No match found</p>



    <div class="row g-4">
        <?php
        require '../../database/connection.php';

       // Number of candidates per page
            $limit = 8; 
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            // Search Filter
            $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

            // Adjust query based on search
            $whereClause = !empty($search) ? "WHERE full_name LIKE '%$search%'" : "";

            // Sorting: First by candidate_number, then alternating category order
            $query = "SELECT * FROM contestants 
                    $whereClause 
                    ORDER BY candidate_number ASC, 
                            CASE 
                                WHEN category = 'MISS ADFC' THEN 1 
                                WHEN category = 'MISTER ADFC' THEN 2 
                                ELSE 3 
                            END 
                    LIMIT $limit OFFSET $offset";
            $result = mysqli_query($conn, $query);

            // Get total count for pagination
            $countQuery = "SELECT COUNT(*) as total FROM contestants $whereClause";
            $countResult = mysqli_query($conn, $countQuery);
            $totalRows = mysqli_fetch_assoc($countResult)['total'];
            $totalPages = ceil($totalRows / $limit);


        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $photo = !empty($row['photo']) ? "../../uploads/{$row['photo']}" : "../../assets/img/default-avatar.png";
                $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); // Prevent XSS

                echo "
                <div class='col-lg-3 col-md-6'> <!-- 4 Cards per row -->
                    <div class='card border-0 shadow-lg rounded-4 overflow-hidden candidate-card' style='height: 500px; position: relative;'>
                        
                        <!-- Image -->
                        <img src='{$photo}' alt='Profile Picture' class='w-100 h-100' style='object-fit: cover;'>

                        <!-- Burger Menu -->
                        <div class='dropdown position-absolute top-0 end-0 mt-2 me-2'>
                            <button class='burger-icon' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                <i class='fas fa-bars'></i> <!-- Burger Icon -->
                            </button>
                            <ul class='dropdown-menu dropdown-menu-end'>
                                <li><button class='dropdown-item' onclick='openEditModal({$rowData})'><i class='fas fa-edit'></i> Edit</button></li>
                                <li><button class='dropdown-item text-danger' onclick='confirmDelete({$row['id']})'><i class='fas fa-trash-alt'></i> Delete</button></li>
                            </ul>
                        </div>

                        <!-- Info Section with Soft Overlay -->
                        <div class='info-overlay position-absolute bottom-0 start-0 w-100 text-white p-3'>
                            <h5 class='fw-bold m-0'>{$row['full_name']} <span class='fs-3 fw-bold text-light'>({$row['candidate_number']})</span></h5>
                            <p class='badge bg-secondary'>{$row['category']}</p>
                            <p class='m-0 small'>Age: {$row['age']} | Dept: {$row['department']}</p>
                            <p class='m-0 small'>Course: {$row['course']}</p>
                            <p class='m-0 small'>Year: {$row['year']}</p>

                            <!-- Bio and Motto (Initially Hidden, Show on Hover) -->
                            <div class='extra-info mt-2' style='display: none;'>
                                <blockquote class='blockquote mb-1'>
                                    <p class='fst-italic'>&quot;{$row['motto']}&quot;</p>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "<p class='text-center text-danger fw-bold'>No candidates found</p>";
        }

        mysqli_close($conn);
        ?>
    </div>

    <!-- Pagination Controls -->
    <nav class="mt-4">
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

</div>


</div>

<?php include '../components/modals/add_candidates_modal.php'; ?>

<!-- Bootstrap and jQuery JS -->
<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/sweetalert2.min.js"></script>
<script src="../../assets/js/candidates.js"></script>



<!-- JavaScript for Search -->
<script>
function searchCandidates() {
    let input = document.getElementById("searchInput").value.trim();
    let url = new URL(window.location.href);
    
    if (input) {
        url.searchParams.set("search", input); // Update search query in URL
        url.searchParams.set("page", 1); // Reset to first page
    } else {
        url.searchParams.delete("search"); // Remove search query if empty
    }

    window.history.pushState({}, "", url); // Update URL without refresh

    // Fetch the updated candidates list dynamically
    fetch(url)
        .then(response => response.text())
        .then(html => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(html, "text/html");
            let candidatesContent = doc.querySelector(".row.g-4").innerHTML;
            document.querySelector(".row.g-4").innerHTML = candidatesContent;
            
            let paginationContent = doc.querySelector(".pagination").innerHTML;
            document.querySelector(".pagination").innerHTML = paginationContent;
        })
        .catch(error => console.error("Error fetching search results:", error));
}


</script>

</body>
</html>