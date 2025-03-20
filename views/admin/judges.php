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
    <title>Manage Judges | ADFC Pageant System</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/mystyle.css"> 
    <link href="../../assets/css/datatables.min.css" rel="stylesheet">
</head>
<body>

<!-- Include Sidebar & Topbar -->
<?php include '../components/sidebar.php'; ?>
<?php include '../components/topbar.php'; ?>

<div class="content" id="content">
    <h3>Judges Management</h3>

    <div class="d-flex justify-content-end">
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addJudgeModal">
            <i class="fas fa-plus"></i> Add Judge
        </button>
    </div>

    <div class="card shadow-sm rounded-3">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Judges List</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table id="judgesTable" class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    require '../../database/connection.php';

                    $query = "SELECT id, username FROM users WHERE role = 'judge'";
                    $result = mysqli_query($conn, $query);
                    $counter = 1;

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr class='align-middle'>
                                <td class='text-center'>{$counter}</td>
                                <td class='fw-bold '>{$row['username']}</td>
                                <td class='text-center'>
                                    <button class='btn btn-sm btn-primary me-2' title='Edit Judge' onclick='openEditModal({$row['id']}, \"{$row['username']}\")'>
                                        <i class='fas fa-edit'></i> Edit
                                    </button>
                                    <button class='btn btn-sm btn-danger' title='Delete Judge' onclick='confirmDelete({$row['id']})'>
                                        <i class='fas fa-trash-alt'></i> Delete
                                    </button>
                                </td>
                            </tr>";
                        $counter++;
                    }

                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../components/modals/add_judges_modal.php'; ?>



<!-- Bootstrap and jQuery JS -->
<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/sweetalert2.min.js"></script>
<script src="../../assets/js/datatables.min.js"></script>
<script src="../../assets/js/judges.js"></script>


<script>
 $(document).ready(function () {
    $("#judgesTable").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "lengthMenu": [5, 10, 25, 50],
        "pageLength": 5,
        "language": {
            "emptyTable": "No judges found",
            "search": "Search Criteria:",
            "lengthMenu": "Show _MENU_ entries"
        }
    });
});

</script>

</script>


</body>
</html>
