<?php
$jsonData = isset($_GET['data']) ? $_GET['data'] : '[]';
$tableData = json_decode(urldecode($jsonData), true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printable Results | ADFC Pageant System</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <style>
    body {
        font-size: 10pt;
    }
    table {
        width: 100%;
    }
    .header-container {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    .logo {
        max-height: 80px; /* Adjust as needed */
        margin-right: 20px;
    }
    .title {
        text-align: center;
    }
    @media print {
        #printButton {
            display: none;
        }
    }
</style>

</head>
<body>
    <div class="container">
        <div class="header-container">
            <img src="../../assets/image/adflogo.png" alt="logo" class="logo">
            <h4 class="title">Mister and Miss ADFC <?php echo date("Y"); ?></h4>
        </div>
        <table class="table table-bordered table-hover">
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
                <?php if (!empty($tableData)): ?>
                    <?php foreach ($tableData as $row): ?>
                        <tr>
                            <td><?php echo $row['rank']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['criteria']; ?></td>
                            <td><?php echo $row['score']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="text-center">
            <button id="printButton" class="btn btn-primary mt-3">Print</button>
        </div>
    </div>
    <script>
        document.getElementById('printButton').addEventListener('click', function() {
            window.print();
        });
    </script>
</body>
</html>