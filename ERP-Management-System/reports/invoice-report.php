<?php
include "../config/database.php";

$start_date = $_GET['start_date'] ?? "";
$end_date = $_GET['end_date'] ?? "";

$sql = "SELECT invoices.id, invoices.invoice_number, invoices.invoice_date, 
               customers.title, customers.first_name, customers.last_name, customers.district,
               COALESCE(SUM(invoice_items.quantity), 0) AS item_count,
               COALESCE(SUM(invoice_items.quantity * invoice_items.unit_price), 0) AS total_amount
        FROM invoices 
        JOIN customers ON invoices.customer_id = customers.id 
        LEFT JOIN invoice_items ON invoices.id = invoice_items.invoice_id";

//date_range
if (!empty($start_date) && !empty($end_date)) {
    $sql .= " WHERE invoices.invoice_date BETWEEN '$start_date' AND '$end_date'";
}

$sql .= " GROUP BY invoices.id, invoices.invoice_number, invoices.invoice_date, customers.title, customers.first_name, customers.last_name, customers.district 
          ORDER BY invoices.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Report</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Invoice Summary Report</h2>
            <button onclick="window.print();" class="btn btn-success no-print">Print Report</button>
        </div>

        <!--search form for date range-->
        <div class="card shadow-sm border p-3 mb-4 no-print">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($start_date); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($end_date); ?>" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="invoice-report.php" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="card shadow-sm border p-3">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Invoice Number</th>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th>Customer District</th>
                        <th>Item Count</th>
                        <th>Invoice Amount (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        $i = 1;
                        $grand_total = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $total = $row['total_amount'] ?? 0;
                            $grand_total += $total;
                            $customer_name = trim(($row['title'] ?? '') . ' ' . ($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));

                            echo "<tr>";
                            echo "<td>" . $i++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['invoice_number'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['invoice_date'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($customer_name ?: 'Walk-in Customer') . "</td>";
                            echo "<td>" . htmlspecialchars($row['district'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['item_count']) . "</td>";
                            echo "<td>Rs. " . number_format($total, 2) . "</td>";
                            echo "</tr>";
                        }
                        echo "<tr class='fw-bold table-secondary'>";
                        echo "<td colspan='6' class='text-end'>Grand Total Revenue:</td>";
                        echo "<td>Rs. " . number_format($grand_total, 2) . "</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No invoices found for the selected date range!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 no-print">
            <a href="index.php" class="btn btn-secondary">Back to Reports</a>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>