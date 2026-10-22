<?php
include "../config/database.php";

$categories_result = mysqli_query($conn, "SELECT * FROM categories");
$subcategories_result = mysqli_query($conn, "SELECT * FROM subcategories");

if (isset($_POST['save'])) {
    $item_code = trim($_POST['item_code'] ?? "");
    $item_name = trim($_POST['item_name'] ?? "");
    $category_id = trim($_POST['category_id'] ?? "");
    $subcategory_id = trim($_POST['subcategory_id'] ?? "");
    $quantity = trim($_POST['quantity'] ?? "");
    $unit_price = trim($_POST['unit_price'] ?? "");

    if (
        empty($item_code) || empty($item_name) || empty($category_id) || empty($subcategory_id) || empty($quantity) || empty($unit_price)
    ) {
        //don't display a PHP message
    }
    elseif (!is_numeric($quantity) || $quantity < 0 || !is_numeric($unit_price) || $unit_price < 0) {
        //invalid quantity or price
    }
    else {
        $check_sql = "SELECT * FROM items WHERE item_code = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $item_code);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('This Item Code already exists! Please use a different one.');</script>";
        } else {
            $sql = "INSERT INTO items (item_code, item_name, category_id, subcategory_id, quantity, unit_price) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param(
                    $stmt, "ssiiid",
                    $item_code,
                    $item_name,
                    $category_id,
                    $subcategory_id,
                    $quantity,
                    $unit_price
                );

                if(mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('Item added Successfully!'); window.location.href='add.php';</script>";
                } else {
                    echo "<script>alert('Error adding item!');</script>";
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "<script>alert('Database query preparation failed!');</script>";
            }
        }
        mysqli_stmt_close($check_stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adding Item</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Item Registration</h2>
            <a href="../index.php" class="btn btn-secondary">Back to Home</a>
        </div>
        <div class="card shadow-sm border p-3">
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Item Code</label>
                <input type="text" name="item_code" class="form-control">
            </div>  
            
            <div class="mb-3">
                <label class="form-label">Item Name</label>
                <input type="text" name="item_name" class="form-control">
            </div>  

            <div class="mb-3">
                <label class="form-label">Item Category</label>
                <select name="category_id" class="form-select" required>
                    <option value=""selected disabled>--Select Category--</option>
                    <?php if ($categories_result) {
                        while ($cat = mysqli_fetch_assoc($categories_result))
                        {
                            echo"<option value='".$cat['id']."'>".$cat['category_name']."</option>";
                        }
                    }
                    ?>
                </select>
            </div>  

            <div class="mb-3">
                <label class="form-label">Item Sub Category</label>
                <select name="subcategory_id" class="form-select" required>
                    <option value=""selected disabled>--Select Category--</option>
                    <?php if ($subcategories_result) {
                        while ($cat = mysqli_fetch_assoc($subcategories_result))
                        {
                            echo"<option value='".$cat['id']."'>".$cat['subcategory_name']."</option>";
                        }
                    }
                    ?>
                </select>
            </div>  

            <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control">
            </div>  

            <div class="mb-3">
                <label class="form-label">Unit Price</label>
                <input type="number" step="0.01" name="unit_price" class="form-control">
            </div>  

            <button type="submit" name="save" class="btn btn-primary">Save Item</button>
            <button type="reset" class="btn btn-secondary">Clear</button>
        </form>
        </div>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function(event) {

            let itemCode = document.querySelector('[name="item_code"]').value.trim();
            let itemName = document.querySelector('[name="item_name"]').value.trim();
            let categoryId = document.querySelector('[name="category_id"]').value.trim();
            let subcategoryId = document.querySelector('[name="subcategory_id"]').value.trim();
            let quantity = document.querySelector('[name="quantity"]').value.trim();
            let unitPrice = document.querySelector('[name="unit_price"]').value.trim();

            if (itemCode === "" || itemName === "" || category_id === "" || subcategoryId === "" || quantity === "" || unitprice === "") {
                alert("Please fill in all required fields!");
                event.preventDefault();
            }
            else if (quantity < 0 || isNaN(quantity)) {
                alert("Please enter a valid quantity!");
                event.preventDefault();
            }
            else if (unitPrice < 0 || isNaN(unitPrice)) {
                alert("Please enter a valid unit price!");
            }
        });
    </script>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    </script>
</body>
</html>