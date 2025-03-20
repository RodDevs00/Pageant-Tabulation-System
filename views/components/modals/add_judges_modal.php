
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