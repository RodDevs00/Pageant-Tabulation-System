<!-- Add Criteria Modal -->
<div class="modal fade" id="addCriteriaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Criteria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCriteriaForm">
                    <div class="mb-3">
                        <label for="criteria_name" class="form-label">Criteria Name</label>
                        <input type="text" id="criteria_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                    <label for="criteria_category" class="form-label">Category</label>
                    <select id="criteria_category" class="form-control" required>
                        <option value="" disabled selected>Select Category</option>
                        <option value="MISTER ADFC">MISTER ADFC</option>
                        <option value="MISS ADFC">MISS ADFC</option>
                    </select>
                </div>
                    <div class="mb-3">
                        <label for="criteria_percentage" class="form-label">Percentage (%)</label>
                        <input type="number" id="criteria_percentage" class="form-control" required min="1" max="100">
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Criteria Modal -->
<div class="modal fade" id="editCriteriaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Criteria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCriteriaForm">
                    <input type="hidden" id="edit_criteria_id">
                    <div class="mb-3">
                        <label for="edit_criteria_name" class="form-label">Criteria Name</label>
                        <input type="text" id="edit_criteria_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_criteria_category" class="form-label">Category</label>
                        <select id="edit_criteria_category" class="form-control" required>
                            <option value="" disabled selected>Select Category</option>
                            <option value="MISTER ADFC">MISTER ADFC</option>
                            <option value="MISS ADFC">MISS ADFC</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_criteria_percentage" class="form-label">Percentage (%)</label>
                        <input type="number" id="edit_criteria_percentage" class="form-control" required min="1" max="100">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>