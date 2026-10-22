<?php
include "../config/database.php";

$sql = "SELECT items.item_name, 
               categories.category_name AS item_category, 
               subcategories.subcategory_name AS item_sub_category, 
               SUM(items.quantity) AS total_quantity 
        FROM items 
        JOIN categories ON items.category_id = categories.id 
        JOIN subcategories ON items.subcategory_id = subcategories.id 
        GROUP BY items.item_name, categories.category_name, subcategories.subcategory_name 
        ORDER BY items.item_name ASC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Report</title>
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
            <h2>Item Stock Report</h2>
            <button onclick="window.print();" class="btn btn-success no-print">Print Report</button>
        </div>

        <div class="card shadow-sm border p-3">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $i++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['item_category'] ?? '-') . "</td>";
                            echo "<td>" . htmlspecialchars($row['item_sub_category'] ?? '-') . "</td>";
                            echo "<td>" . htmlspecialchars($row['total_quantity']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No items found!</td></tr>";
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