<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.html");
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "adfc_pageant");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch average scores and rankings from the database
$query = "SELECT
                    c.id AS candidate_id,
                    c.full_name,
                    c.category,
                    cr.name AS criteria_name,
                    AVG(s.score) AS average_score,
                    RANK() OVER (PARTITION BY s.criterion_id ORDER BY AVG(s.score) DESC) AS ranking
                FROM scores s
                JOIN contestants c ON s.contestant_id = c.id
                JOIN criteria cr ON s.criterion_id = cr.id
                GROUP BY c.id, c.full_name, c.category, cr.name, s.criterion_id
                ORDER BY c.category, cr.name, ranking";

$result = $mysqli->query($query);

$candidates = [];
$criteria = [];

while ($row = $result->fetch_assoc()) {
    $criteria[$row['criteria_name']] = true; // Collect unique criteria
    $candidates[$row['category']][] = $row;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results | ADFC Pageant System</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/mystyle.css">
    <link rel="stylesheet" href="../../assets/css/datatables.min.css">
</head>
<body>

<?php include '../components/sidebar.php'; ?>
<?php include '../components/topbar.php'; ?>

<div class="content" id="content">
    <h3 class="mb-4">Results</h3>

    <div class="card shadow-sm rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Candidates Ranking</h5>
            <button id="printTable" class="btn btn-light btn-sm"><i class="fas fa-print"></i> Print Page</button>
        </div>
        <div class="card-body">
            <div class="mb-3 d-flex gap-3">
                <select id="categoryFilter" class="form-select w-auto">
                    <option value="all">Show All</option>
                    <option value="MISS ADFC">Miss ADFC</option>
                    <option value="MISTER ADFC">Mister ADFC</option>
                </select>
                <select id="criteriaFilter" class="form-select w-auto">
                    <option value="all">All Criteria</option>
                    <?php foreach ($criteria as $criterion => $_) { ?>
                        <option value="<?php echo $criterion; ?>"><?php echo $criterion; ?></option>
                    <?php } ?>
                </select>
                <select id="rankFilter" class="form-select w-auto">
                    <option value="all">All Ranks</option>
                    <option value="1">Rank 1 Only</option>
                </select>
            </div>

            <div class="table-responsive">
                <table id="resultsTable" class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Rank</th>
                            <th>Candidate Name</th>
                            <th>Category</th>
                            <th>Criteria</th>
                            <th>Average Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($candidates)): ?>
                            <?php foreach ($candidates as $category => $entries): ?>
                                <?php foreach ($entries as $candidate): ?>
                                    <tr data-category="<?php echo $candidate['category']; ?>" data-criteria="<?php echo $candidate['criteria_name']; ?>" data-rank="<?php echo $candidate['ranking']; ?>">
                                        <td><?php echo $candidate['ranking']; ?></td>
                                        <td><?php echo $candidate['full_name']; ?></td>
                                        <td><?php echo $candidate['category']; ?></td>
                                        <td><?php echo $candidate['criteria_name']; ?></td>
                                        <td><?php echo number_format($candidate['average_score'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/datatables.min.js"></script>
<script>
$(document).ready(function () {
    var table = $("#resultsTable").DataTable(); // Enable DataTables

    $("#categoryFilter, #criteriaFilter, #rankFilter").change(function () {
        var selectedCategory = $("#categoryFilter").val();
        var selectedCriteria = $("#criteriaFilter").val();
        var selectedRank = $("#rankFilter").val();

        table.rows().every(function () {
            var row = $(this.node());
            var rowCategory = row.data("category");
            var rowCriteria = row.data("criteria");
            var rowRank = row.data("rank");

            if ((selectedCategory === "all" || rowCategory === selectedCategory) &&
                (selectedCriteria === "all" || rowCriteria === selectedCriteria) &&
                (selectedRank === "all" || rowRank == selectedRank)) {
                row.show();
            } else {
                row.hide();
            }
        });
        table.draw();
    });

    $("#printTable").click(function() {
        var tableData = [];
        $("#resultsTable tbody tr:visible").each(function() {
            var rowData = {
                rank: $(this).find("td:eq(0)").text(),
                name: $(this).find("td:eq(1)").text(),
                category: $(this).find("td:eq(2)").text(),
                criteria: $(this).find("td:eq(3)").text(),
                score: $(this).find("td:eq(4)").text()
            };
            tableData.push(rowData);
        });

        var jsonData = JSON.stringify(tableData);

        window.open('print_results.php?data=' + encodeURIComponent(jsonData), '_blank');
    });
});
</script>

</body>
</html>