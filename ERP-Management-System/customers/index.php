<?php
include "../config/database.php";

$sql = "SELECT * FROM customers";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Customer List</h2>
            <div class="d-flex gap-2">
            <a href="../index.php" class="btn btn-secondary">Back to Home</a>
            <a href="add.php" class="btn btn-primary">+ Add New Customer</a>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Contact Number</th>
                    <th>District</th>
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
                        echo "<td>" . htmlspecialchars($row['title'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['first_name'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['last_name'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['contact_number'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['district'] ?? '') . "</td>";
                        echo "<td>
                                <a href='edit.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning'>Edit</a>
                                <a href='delete.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this customer?\");'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No customers found!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>