<?php
include "../config/database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>ERP System Reports Dashboard</h2>
            <a href="../index.php" class="btn btn-secondary">Back to Home</a>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Item Stock Report</h5>
                        <p class="card-text">View complete inventory stock, quantity, and total valuation.</p>
                        <a href="item-report.php" class="btn btn-primary">View Report</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Invoice Report</h5>
                        <p class="card-text">View overall invoice summaries and total revenue generated.</p>
                        <a href="invoice-report.php" class="btn btn-primary">View Report</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Invoice Item Report</h5>
                        <p class="card-text">View detailed item-wise breakdown for all invoices sold.</p>
                        <a href="invoice-item-report.php" class="btn btn-primary">View Report</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>