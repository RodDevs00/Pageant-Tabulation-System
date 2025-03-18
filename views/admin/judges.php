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
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require '../../database/connection.php'; // Ensure this is the correct path

                    $query = "SELECT id, username FROM users WHERE role = 'judge'";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        $counter = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr class='align-middle'>
                                    <td class='text-center'>{$counter}</td>
                                    <td class='fw-bold '>{$row['username']}</td>
                                    <td class='text-center'>
                                       <button class='btn btn-sm btn-outline-success me-2' title='Edit Judge' onclick='openEditModal({$row['id']}, \"{$row['username']}\")'>
                                            <i class='fas fa-edit'></i> Edit
                                        </button>
                                        <button class='btn btn-sm btn-outline-danger' title='Delete Judge' 
                                            onclick='confirmDelete({$row['id']})'>
                                            <i class='fas fa-trash-alt'></i> Delete
                                        </button>
                                    </td>
                                </tr>";
                            $counter++;
                        }
                    } else {
                        echo "<tr>
                                <td colspan='4' class='text-center text-danger fw-bold'>No judges found</td>
                              </tr>";
                    }

                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Add Judge Modal -->
<div class="modal fade" id="addJudgeModal" tabindex="-1" aria-labelledby="addJudgeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addJudgeModalLabel">Add New Judge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addJudgeForm">
                    <div class="mb-3">
                        <label for="judgeName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="judgeName" name="username" placeholder="Enter judge's username" required>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="judgePassword" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="judgePassword" name="password" placeholder="Enter password" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('judgePassword', 'toggleAddJudgeIcon')">
                                <i id="toggleAddJudgeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary w-100" onclick="addJudge()">Add Judge</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Judge Modal -->
<div class="modal fade" id="editJudgeModal" tabindex="-1" aria-labelledby="editJudgeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editJudgeModalLabel">Edit Judge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editJudgeForm">
                    <input type="hidden" id="editJudgeId" name="id">
                    <div class="mb-3">
                        <label for="editJudgeName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editJudgeName" name="username" required>
                    </div>
                    <div class="mb-3 position-relative">
                    <label for="editJudgePassword" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="editJudgePassword" name="password" placeholder="Leave blank to keep current password">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('editJudgePassword', 'toggleEditJudgeIcon')">
                            <i id="toggleEditJudgeIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                    <button type="button" class="btn btn-primary w-100" onclick="updateJudge()">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Bootstrap and jQuery JS -->
<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/sweetalert2.min.js"></script>

<script>
   function addJudge() {
    const formData = {
        username: $('#judgeName').val(),
        password: $('#judgePassword').val()
    };

    $.ajax({
        type: 'POST',
        url: '../../controllers/add_judge.php',
        data: formData,
        success: function(response) {
            Swal.fire({
                title: 'Success!',
                text: response,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                $('#addJudgeModal').modal('hide');
                location.reload(); // Refresh table
            });
        },
        error: function() {
            Swal.fire({
                title: 'Error!',
                text: 'Error adding judge. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}

</script>

<script>
    // Open the edit modal and populate fields
function openEditModal(id, username) {
    $('#editJudgeId').val(id);
    $('#editJudgeName').val(username);
    $('#editJudgeModal').modal('show');
}

function updateJudge() {
    const formData = {
        id: $('#editJudgeId').val(),
        username: $('#editJudgeName').val(),
        password: $('#editJudgePassword').val()
    };

    $.ajax({
        type: 'POST',
        url: '../../controllers/update_judge.php',
        data: formData,
        success: function(response) {
            Swal.fire({
                title: 'Success!',
                text: response,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                $('#editJudgeModal').modal('hide');
                location.reload(); // Refresh table
            });
        },
        error: function() {
            Swal.fire({
                title: 'Error!',
                text: 'Error updating judge. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}


function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}


</script>


<script>
 function confirmDelete(judgeId) {
    judgeIdToDelete = judgeId;

    Swal.fire({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this judge's data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            deleteJudge();
        }
    });
}

function deleteJudge() {
    if (judgeIdToDelete) {
        $.ajax({
            type: 'POST',
            url: '../../controllers/delete_judge.php',
            data: { judge_id: judgeIdToDelete },
            dataType: 'json',
            success: function(response) {
                Swal.fire({
                    title: "Success!",
                    text: response.message,
                    icon: "success"
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: "Oops!",
                    text: "Failed to delete judge. Please try again.",
                    icon: "error"
                });
            }
        });
    }
}


</script>

</body>
</html>
