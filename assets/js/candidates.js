// ADD CANDIDATES
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
// END ADD CANDIDATES

//Delete
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

//End Delete

// EDIT CANDIDATES

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

// END EDIT CANDIDATES

