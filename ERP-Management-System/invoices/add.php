<?php

include "../config/database.php";

if (isset($_POST['save'])) {

    $customer_id = $_POST['customer_id'] ?? "";
    $item_id = $_POST['item_id'] ?? "";
    $quantity = trim($_POST['quantity'] ?? "");
    $unit_price = trim($_POST['unit_price'] ?? "");
    
    $invoice_number = "INV-" . rand(1000, 9999);
    $invoice_date = date('Y-m-d');

    if (
        empty($customer_id) ||
        empty($item_id) ||
        empty($quantity) ||
        empty($unit_price)
    ) {
        //don't display a PHP message
    }
    elseif (!is_numeric($quantity) || $quantity <= 0) {
        //invalid quantity
    }
    elseif (!is_numeric($unit_price) || $unit_price < 0) {
        //invalid unit price
    }
    else {
        $sql = "INSERT INTO invoices (invoice_number, invoice_date, customer_id) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "ssi",
                $invoice_number,
                $invoice_date,
                $customer_id
            );

            if (mysqli_stmt_execute($stmt)) {
                $invoice_id = mysqli_insert_id($conn);

                $sql_item = "INSERT INTO invoice_items (invoice_id, item_id, quantity, unit_price) VALUES (?, ?, ?, ?)";
                $stmt_item = mysqli_prepare($conn, $sql_item);

                if ($stmt_item) {
                    mysqli_stmt_bind_param(
                        $stmt_item,
                        "iiid",
                        $invoice_id,
                        $item_id,
                        $quantity,
                        $unit_price
                    );

                    if (mysqli_stmt_execute($stmt_item)) {
                        echo "<script>alert('Invoice created successfully!');</script>";
                    } else {
                        echo "<script>alert('Error adding invoice items!');</script>";
                    }

                    mysqli_stmt_close($stmt_item);
                } else {
                    echo "<script>alert('Invoice items query preparation failed!');</script>";
                }

            } else {
                echo "<script>alert('Error creating invoice!');</script>";
            }

            mysqli_stmt_close($stmt);

        } else {
            echo "<script>alert('Database query preparation failed!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Create Invoice</h2>
            <a href="../index.php" class="btn btn-secondary">Back to Home</a>
        </div>
        <div class="card shadow-sm border p-3">
        <form method="POST" action="">
            
            <!-- Customer Select -->
            <div class="mb-3">
                <label class="form-label">Customer</label>
                <select name="customer_id" class="form-select" required>
                    <option value="" selected disabled>--Select Customer--</option>
                    <?php
                    $cust_res = mysqli_query($conn, "SELECT * FROM customers");
                    if ($cust_res && mysqli_num_rows($cust_res) > 0) {
                        while ($c = mysqli_fetch_assoc($cust_res)) {
                            echo '<option value="'.$c['id'].'">'.$c['first_name'].' '.$c['last_name'].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Item Select -->
            <div class="mb-3">
                <label class="form-label">Item</label>
                <select name="item_id" class="form-select" required>
                    <option value="" selected disabled>--Select Item--</option>
                    <?php
                    $item_res = mysqli_query($conn, "SELECT * FROM items");
                    if ($item_res && mysqli_num_rows($item_res) > 0) {
                        while ($i = mysqli_fetch_assoc($item_res)) {
                            echo '<option value="'.$i['id'].'">'.$i['item_name'].' (Stock: '.$i['quantity'].')</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Quantity -->
            <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" min="1">
            </div>

            <!-- Unit Price -->
            <div class="mb-3">
                <label class="form-label">Unit Price</label>
                <input type="number" step="0.01" name="unit_price" class="form-control" min="0">
            </div>

            <button type="submit" name="save" class="btn btn-primary">Save Invoice</button>
            <button type="reset" class="btn btn-secondary">Clear</button>

        </form>
        </div>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function(event) {

            let customerId = document.querySelector('[name="customer_id"]').value;
            let itemId = document.querySelector('[name="item_id"]').value;
            let quantity = document.querySelector('[name="quantity"]').value.trim();
            let unitPrice = document.querySelector('[name="unit_price"]').value.trim();

            if (customerId === "" || itemId === "" || quantity === "" || unitPrice === "") {
                alert("Please fill in all required fields!");
                event.preventDefault();
            }
            else if (isNaN(quantity) || quantity <= 0) {
                alert("Please enter a valid quantity!");
                event.preventDefault();
            }
            else if (isNaN(unitPrice) || unitPrice < 0) {
                alert("Please enter a valid unit price!");
                event.preventDefault();
            }
        });
    </script>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>