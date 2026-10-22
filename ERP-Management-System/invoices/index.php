<?php
include "../config/database.php";

$sql = "SELECT invoices.*, customers.first_name, customers.last_name 
        FROM invoices 
        JOIN customers ON invoices.customer_id = customers.id 
        ORDER BY invoices.id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Invoices</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Invoice List</h2>
            <div class="d-flex gap-2">
            <a href="../index.php" class="btn btn-secondary">Back to Home</a>
            <a href="add.php" class="btn btn-primary">+ Create New Invoice</a>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Invoice Number</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $i++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['invoice_number'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['invoice_date'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? '')) . "</td>";
                        echo "<td>
                                <a href='edit.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning'>Edit</a>
                                <a href='delete.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this invoice?\");'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No invoices found!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>