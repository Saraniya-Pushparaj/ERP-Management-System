<?php
include "../config/database.php";

$start_date = $_GET['start_date'] ?? "";
$end_date = $_GET['end_date'] ?? "";

$sql = "SELECT invoice_items.*, invoices.invoice_number, invoices.invoice_date, 
               customers.title, customers.first_name, customers.last_name, 
               items.item_code, items.item_name, 
               categories.category_name AS item_category, 
               subcategories.subcategory_name AS item_sub_category 
                FROM invoice_items 
                JOIN invoices ON invoice_items.invoice_id = invoices.id 
                JOIN customers ON invoices.customer_id = customers.id 
                JOIN items ON invoice_items.item_id = items.id 
                JOIN categories ON items.category_id = categories.id 
                JOIN subcategories ON items.subcategory_id = subcategories.id";

if (!empty($start_date) && !empty($end_date)) {
    $sql .= " WHERE invoices.invoice_date BETWEEN '$start_date' AND '$end_date'";
}

$sql .= " ORDER BY invoices.id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Item-wise Report</title>
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
            <h2>Invoice Item-wise Report</h2>
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
                    <a href="invoice-item-report.php" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="card shadow-sm border p-3">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Invoice No</th>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th>Item Name (Code)</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Quantity</th>
                        <th>Unit Price (Rs.)</th>
                        <th>Total (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        $i = 1;
                        $grand_total = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $total = $row['quantity'] * $row['unit_price'];
                            $grand_total += $total;
                            $customer_name = trim(($row['title'] ?? '') . ' ' . ($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));

                            echo "<tr>";
                            echo "<td>" . $i++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['invoice_number'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['invoice_date'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($customer_name ?: 'Walk-in Customer') . "</td>";
                            echo "<td>" . htmlspecialchars($row['item_name']) . " (Code: " . htmlspecialchars($row['item_code']) . ")</td>";
                            echo "<td>" . htmlspecialchars($row['item_category'] ?? '-') . "</td>";
                            echo "<td>" . htmlspecialchars($row['item_sub_category'] ?? '-') . "</td>";
                            echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                            echo "<td>Rs. " . number_format($row['unit_price'], 2) . "</td>";
                            echo "<td>Rs. " . number_format($total, 2) . "</td>";
                            echo "</tr>";
                        }
                        echo "<tr class='fw-bold table-secondary'>";
                        echo "<td colspan='9' class='text-end'>Grand Total:</td>";
                        echo "<td>Rs. " . number_format($grand_total, 2) . "</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr><td colspan='10' class='text-center'>No records found for the selected date range!</td></tr>";
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