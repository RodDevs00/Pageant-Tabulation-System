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
                            <select id="category" class="form-control mb-2">
                                <option value="MISS ADFC">Miss ADFC</option>
                                <option value="MISTER ADFC">Mister ADFC</option>
                            </select>
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
                            <select id="editCategory" class="form-control mb-2">
                                <option value="MISS ADFC">Miss ADFC</option>
                                <option value="MISTER ADFC">Mister ADFC</option>
                            </select>
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
                <button class="btn btn-primary" onclick="updateCandidate()">Update</button>
            </div>
        </div>
    </div>
</div>