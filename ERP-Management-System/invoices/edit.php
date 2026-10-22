<?php
include "../config/database.php";

$id = $_GET['id'] ?? "";
if (empty($id)) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM invoices WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$invoice = mysqli_fetch_assoc($result);

if (!$invoice) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['update'])) {
    $customer_id = $_POST['customer_id'] ?? "";
    $invoice_date = $_POST['invoice_date'] ?? "";

    if (empty($customer_id) || empty($invoice_date)) {
        // Validation check
    } else {
        $update_sql = "UPDATE invoices SET customer_id = ?, invoice_date = ? WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);

        if ($update_stmt) {
            mysqli_stmt_bind_param($update_stmt, "isi", $customer_id, $invoice_date, $id);

            if (mysqli_stmt_execute($update_stmt)) {
                echo "<script>alert('Invoice updated successfully!'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Error updating invoice!');</script>";
            }

            mysqli_stmt_close($update_stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Invoice</h2>
            <a href="index.php" class="btn btn-secondary">Back to List</a>
        </div>
        <div class="card shadow-sm border p-3">
        <form method="POST" action="">
            
            <!--customer_select-->
            <div class="mb-3">
                <label class="form-label">Customer</label>
                <select name="customer_id" class="form-select" required>
                    <option value="" disabled>--Select Customer--</option>
                    <?php
                    $cust_res = mysqli_query($conn, "SELECT * FROM customers");
                    if ($cust_res && mysqli_num_rows($cust_res) > 0) {
                        while ($c = mysqli_fetch_assoc($cust_res)) {
                            $selected = ($c['id'] == $invoice['customer_id']) ? "selected" : "";
                            echo '<option value="'.$c['id'].'" '.$selected.'>'.$c['first_name'].' '.$c['last_name'].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <!--invoice_date-->
            <div class="mb-3">
                <label class="form-label">Invoice Date</label>
                <input type="date" name="invoice_date" class="form-control" value="<?php echo htmlspecialchars($invoice['invoice_date']); ?>" required>
            </div>

            <button type="submit" name="update" class="btn btn-primary">Update Invoice</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>

        </form>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>