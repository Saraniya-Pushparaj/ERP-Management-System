<?php
include "../config/database.php";

$sql = "SELECT items.*, categories.category_name, subcategories.subcategory_name 
        FROM items 
        LEFT JOIN categories ON items.category_id = categories.id 
        LEFT JOIN subcategories ON items.subcategory_id = subcategories.id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Items</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Item List</h2>
            <div class="d-flex gap-2">
            <a href="../index.php" class="btn btn-secondary">Back to Home</a>
            <a href="add.php" class="btn btn-primary">+ Add New Item</a>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
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
                        echo "<td>" . htmlspecialchars($row['item_code'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['item_name'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['category_name'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['subcategory_name'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['quantity'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['unit_price'] ?? '') . "</td>";
                        echo "<td>
                                <a href='edit.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning'>Edit</a>
                                <a href='delete.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this item?\");'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No items found!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>