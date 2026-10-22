<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP System Dashboard</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        
        <div class="d-flex justify-content-between align-items-center mb-5 p-3 bg-white rounded shadow-sm">
            <h2>ERP System Dashboard</h2>
            <div>
                <?php if (isset($_SESSION['username'])): ?>
                    <span class="me-3">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                    <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary btn-sm">Login</a>
                <?php endif; ?>
            </div>
        </div>

        <!--dashboard_card-->
        <div class="row">
            <!-- items-->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <h4 class="card-title mb-3">Manage Items</h4>
                        <p class="card-text text-muted">Add, view, edit, and delete inventory items and stock details.</p>
                        <a href="items/index.php" class="btn btn-primary mt-2">Go to Items</a>
                    </div>
                </div>
            </div>

            <!--customer-->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <h4 class="card-title mb-3">Manage Customers</h4>
                        <p class="card-text text-muted">Manage customer records, contact numbers, and district details.</p>
                        <a href="customers/index.php" class="btn btn-success mt-2">Go to Customers</a>
                    </div>
                </div>
            </div>

            <!--invoices-->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <h4 class="card-title mb-3">Manage Invoices</h4>
                        <p class="card-text text-muted">Create new invoices, view bill lists, and manage sales details.</p>
                        <a href="invoices/index.php" class="btn btn-info text-white mt-2">Go to Invoices</a>
                    </div>
                </div>
            </div>

            <!--report-->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <h4 class="card-title mb-3">Reports Dashboard</h4>
                        <p class="card-text text-muted">View item valuation reports, invoice summaries, and print data.</p>
                        <a href="reports/index.php" class="btn btn-warning text-white mt-2">Go to Reports</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>