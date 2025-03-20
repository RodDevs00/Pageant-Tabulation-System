<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.html"); // Redirect if not logged in
    exit;
}

require '../../database/connection.php';

// Fetch criteria from the database
$query = "SELECT * FROM criteria";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criteria | ADFC Pageant System</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css"> 
    <link rel="stylesheet" href="../../assets/css/sweetalert2.min.css">
    <link href="../../assets/css/datatables.min.css" rel="stylesheet">
    <style>
        .table thead th {
            background-color: white !important;
            color: black !important;
        }
    </style>
</head>
<body>

<!-- Include Sidebar & Topbar -->
<?php include '../components/sidebar.php'; ?>
<?php include '../components/topbar.php'; ?>

<div class="content p-4" id="content">

<h3>Criteria Management</h3>

<div class="d-flex justify-content-end">
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCriteriaModal">
        <i class="fas fa-plus"></i> Add Criteria
    </button>
</div>

<div class="card shadow-sm rounded-3">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Criteria List</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <div class="mb-3">
        <div class="position-relative d-inline-block">
    <button id="filterBtn" class="btn btn-secondary btn-sm">
        <i class="fas fa-filter"></i>
    </button>
    <div id="filterDropdown" class="position-absolute top-100 start-0 mt-1 d-none bg-white border rounded shadow-sm p-2" 
         style="width: 150px; z-index: 1000;">
        <button class="dropdown-item btn btn-sm text-start w-100 filter-option" data-filter="all">Show All</button>
        <button class="dropdown-item btn btn-sm text-start w-100 filter-option" data-filter="Mister ADFC">Mister ADFC</button>
        <button class="dropdown-item btn btn-sm text-start w-100 filter-option" data-filter="Miss ADFC">Miss ADFC</button>
    </div>
</div>

    </div>
             <table id="criteriaTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Criteria Name</th>
                        <th>Category</th>
                        <th>Percentage (%)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="criteriaTable">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr id="row-<?= $row['id'] ?>">
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['category'] ?></td>
                            <td><?= $row['percentage'] ?>%</td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-btn" 
                                        data-id="<?= $row['id'] ?>" 
                                        data-name="<?= $row['name'] ?>" 
                                        data-category="<?= $row['category'] ?>" 
                                        data-percentage="<?= $row['percentage'] ?>" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editCriteriaModal">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $row['id'] ?>">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include '../components/modals/add_criteria_modal.php'; ?>

<!-- Bootstrap and jQuery JS -->
<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/sweetalert2.min.js"></script>
<script src="../../assets/js/datatables.min.js"></script>

<script>
$(document).ready(function() {
    // Add Criteria using AJAX
    $("#addCriteriaForm").submit(function(e) {
        e.preventDefault();
        let name = $("#criteria_name").val();
        let category = $("#criteria_category").val();
        let percentage = $("#criteria_percentage").val();

        $.post("../../controllers/add_criteria.php", { name: name, category: category, percentage: percentage }, function(response) {
            Swal.fire("Success", "Criteria added successfully!", "success").then(() => {
                location.reload();
            });
        });
    });

    // Populate Edit Modal
    $(document).on("click", ".edit-btn", function() {
        let id = $(this).data("id");
        let name = $(this).data("name");
        let category = $(this).data("category");
        let percentage = $(this).data("percentage");

        $("#edit_criteria_id").val(id);
        $("#edit_criteria_name").val(name);
        $("#edit_criteria_category").val(category);
        $("#edit_criteria_percentage").val(percentage);
    });

    // Edit Criteria using AJAX
    $("#editCriteriaForm").submit(function(e) {
        e.preventDefault();
        let id = $("#edit_criteria_id").val();
        let name = $("#edit_criteria_name").val();
        let category = $("#edit_criteria_category").val();
        let percentage = $("#edit_criteria_percentage").val();

        $.post("../../controllers/edit_criteria.php", { id: id, name: name, category: category, percentage: percentage }, function(response) {
            Swal.fire("Success", "Criteria updated successfully!", "success").then(() => {
                location.reload();
            });
        });
    });
});

  // Delete Criteria
  $(document).on("click", ".delete-btn", function() {
        let id = $(this).data("id");
        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("../../controllers/delete_criteria.php", { id: id }, function(response) {
                    Swal.fire("Deleted!", "Criteria has been deleted.", "success").then(() => {
                        location.reload();
                    });
                });
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
    const filterBtn = document.getElementById("filterBtn");
    const filterDropdown = document.getElementById("filterDropdown");
    const filterOptions = document.querySelectorAll(".filter-option");
    const tableRows = document.querySelectorAll("#criteriaTable tr");

    // Toggle dropdown visibility
    filterBtn.addEventListener("click", function (event) {
        event.stopPropagation();
        filterDropdown.classList.toggle("d-none");
    });

    // Filter table based on selected category
    filterDropdown.addEventListener("click", function (event) {
        if (event.target.hasAttribute("data-filter")) {
            const selectedCategory = event.target.getAttribute("data-filter").toLowerCase();

            tableRows.forEach(row => {
                const category = row.cells[2].textContent.toLowerCase();
                row.style.display = (selectedCategory === "all" || category.includes(selectedCategory)) ? "" : "none";
            });

            // Hide dropdown after selection
            filterDropdown.classList.add("d-none");
        }
    });

    // Hide dropdown when clicking outside
    document.addEventListener("click", function (event) {
        if (!filterDropdown.classList.contains("d-none") && event.target !== filterBtn) {
            filterDropdown.classList.add("d-none");
        }
    });

    // Add hover effect to dropdown options
    filterOptions.forEach(option => {
        option.addEventListener("mouseenter", function () {
            option.style.backgroundColor = "#f0f0f0"; // Light gray
        });
        option.addEventListener("mouseleave", function () {
            option.style.backgroundColor = ""; // Reset to default
        });
    });
});



</script>
<script>
$(document).ready(function () {
    $("#criteriaTable").DataTable({
        "paging": true, // Enables pagination
        "searching": true, // Enables search bar
        "ordering": true, // Enables sorting
        "lengthMenu": [5, 10, 25, 50], // Pagination options
        "pageLength": 5, // Default records per page
        "language": {
            "search": "Search Criteria:", // Custom search bar text
            "lengthMenu": "Show _MENU_ entries"
        }
    });
});
</script>


</body>
</html>
