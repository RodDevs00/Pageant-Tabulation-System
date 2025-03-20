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


//end add Judge

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
