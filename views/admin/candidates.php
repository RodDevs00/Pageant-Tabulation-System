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
    <link rel="stylesheet" href="../../assets/css/mystyle.css"> 


<!-- Custom Styles -->
<style>
    .candidate-card {
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

.candidate-card:hover {
    transform: scale(1.05); /* Slightly enlarge the card */
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2); /* Add a soft shadow */
}


    /* Soft Gradient Overlay */
    .info-overlay {
        position: absolute;
        bottom: 0;
        width: 100%;
        padding: 20px;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0)); /* Soft fade effect */
        transition: height 0.3s ease-in-out;
        height: 150px; /* Default height */
    }

    .candidate-card:hover .info-overlay {
        height: auto; /* Expand on hover */
    }

    .candidate-card:hover .extra-info {
        display: block !important; /* Show extra details */
    }

    /* White Burger Icon with Shadow */
    .burger-icon {
        color: white;
        border: none;
        font-size: 1.2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3); /* Soft shadow */
        padding: 5px 10px;
        border-radius: 4px;
        background: transparent;
    }

    .burger-icon:hover {
        color: rgba(255, 255, 255, 0.8);
    }

    .dropdown-menu {
        min-width: 120px;
    }
</style>

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
    <input type="text" id="searchInput" class="form-control w-25" placeholder="Search..." onkeyup="searchCandidates()">
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
                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= $search ?>">Previous</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= $search ?>">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>





</div>

<!-- Add Candidate Modal -->
<div class="modal fade" id="addCandidateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <input type="number" id="cno" class="form-control mb-2" placeholder="Candidate Number">
                            <input type="text" id="fullName" class="form-control mb-2" placeholder="Full Name">
                            <input type="text" id="department" class="form-control mb-2" placeholder="Department">
                            <input type="text" id="course" class="form-control mb-2" placeholder="Course">
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <input type="number" id="age" class="form-control mb-2" placeholder="Age">
                            <select id="gender" class="form-control mb-2">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <input type="text" id="year" class="form-control mb-2" placeholder="Year">
                            <input type="text" id="category" class="form-control mb-2" placeholder="Category">
                        </div>
                    </div>

                    <input type="text" id="motto" class="form-control mb-2" placeholder="Motto">
                    <textarea id="bio" class="form-control mb-2" placeholder="Bio"></textarea>
                    <input type="file" id="photo" class="form-control mb-2" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="addCandidate()">Save</button>
            </div>
        </div>
    </div>
</div>


<!-- Edit Candidate Modal -->
<div class="modal fade" id="editCandidateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editId">

                <div class="container">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <input type="number" id="editcno" class="form-control mb-2" placeholder="Candidate Number">
                            <input type="text" id="editFullName" class="form-control mb-2" placeholder="Full Name">
                            <input type="text" id="editDepartment" class="form-control mb-2" placeholder="Department">
                            <input type="text" id="editCourse" class="form-control mb-2" placeholder="Course">
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <input type="number" id="editAge" class="form-control mb-2" placeholder="Age">
                            <select id="editGender" class="form-control mb-2">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <input type="text" id="editYear" class="form-control mb-2" placeholder="Year">
                            <input type="text" id="editCategory" class="form-control mb-2" placeholder="Category">
                        </div>
                    </div>

                    <input type="text" id="editMotto" class="form-control mb-2" placeholder="Motto">
                    <textarea id="editBio" class="form-control mb-2" placeholder="Bio"></textarea>
                    
                    <!-- Image Preview -->
                    <div class="text-center">
                        <img id="editPhotoPreview" src="../../assets/img/default-avatar.png" class="img-fluid mb-2" style="max-height: 200px;">
                    </div>
                    
                    <input type="file" id="editPhoto" class="form-control mb-2" accept="image/*">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary onclick="updateCandidate()">Update</button>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap and jQuery JS -->
<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/sweetalert2.min.js"></script>

<script>
function addCandidate() {
    const formData = new FormData();
    formData.append('cno', $('#cno').val());
    formData.append('full_name', $('#fullName').val());
    formData.append('age', $('#age').val());
    formData.append('gender', $('#gender').val());
    formData.append('department', $('#department').val());
    formData.append('course', $('#course').val());
    formData.append('year', $('#year').val());
    formData.append('category', $('#category').val());
    formData.append('motto', $('#motto').val());
    formData.append('bio', $('#bio').val());
    formData.append('photo', $('#photo')[0].files[0]);

    $.ajax({
        type: 'POST',
        url: '../../controllers/add_candidate.php',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            Swal.fire({
                title: 'Success!',
                text: response.message,
                icon: 'success'
            }).then(() => location.reload());
        },
        error: function() {
            Swal.fire('Oops!', 'Failed to add candidate.', 'error');
        }
    });
}

function confirmDelete(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to undo this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('../../controllers/delete_candidate.php', { id: id }, function(response) {
                Swal.fire('Deleted!', response.message, 'success').then(() => location.reload());
            }).fail(() => {
                Swal.fire('Oops!', 'Failed to delete candidate.', 'error');
            });
        }
    });
}

</script>

<script>
function openEditModal(candidate) {
    $('#editId').val(candidate.id);
    $('#editcno').val(candidate.candidate_number);
    $('#editFullName').val(candidate.full_name);
    $('#editAge').val(candidate.age);
    $('#editGender').val(candidate.gender);
    $('#editDepartment').val(candidate.department);
    $('#editCourse').val(candidate.course);
    $('#editYear').val(candidate.year);
    $('#editCategory').val(candidate.category);
    $('#editMotto').val(candidate.motto);
    $('#editBio').val(candidate.bio);
    
    // Show existing profile picture
    let currentPhoto = candidate.photo ? `../../uploads/${candidate.photo}` : '../../assets/img/default-avatar.png';
    $('#editPhotoPreview').attr('src', currentPhoto);

    $('#editCandidateModal').modal('show');
}

function updateCandidate() {
    const formData = new FormData();
    formData.append('id', $('#editId').val());
    formData.append('editcno', $('#editcno').val());
    formData.append('full_name', $('#editFullName').val());
    formData.append('age', $('#editAge').val());
    formData.append('gender', $('#editGender').val());
    formData.append('department', $('#editDepartment').val());
    formData.append('course', $('#editCourse').val());
    formData.append('year', $('#editYear').val());
    formData.append('category', $('#editCategory').val());
    formData.append('motto', $('#editMotto').val());
    formData.append('bio', $('#editBio').val());

    // Include the photo if a new file is selected
    const photo = $('#editPhoto')[0].files[0];
    if (photo) {
        formData.append('photo', photo);
    }

    $.ajax({
        type: 'POST',
        url: '../../controllers/update_candidate.php',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            Swal.fire('Updated!', response.message, 'success').then(() => location.reload());
        },
        error: function() {
            Swal.fire('Oops!', 'Failed to update candidate.', 'error');
        }
    });
}

</script>


<!-- JavaScript for Search -->
<script>
function searchCandidates() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let cards = document.querySelectorAll(".candidate-card");
    let noMatchMessage = document.getElementById("noMatchMessage");
    let found = false;

    cards.forEach(card => {
        let name = card.querySelector("h5").innerText.toLowerCase();
        let category = card.querySelector(".badge").innerText.toLowerCase();

        if (name.includes(input) || category.includes(input)) {
            card.parentElement.style.display = "block"; // Show matching card
            found = true;
        } else {
            card.parentElement.style.display = "none"; // Hide non-matching card
        }
    });

    // Show "No Match Found" message if no results, hide otherwise
    noMatchMessage.style.display = found ? "none" : "block";
}
</script>

</body>
</html>
