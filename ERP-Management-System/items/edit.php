<?php
include "../config/database.php";

$id = $_GET['id'] ?? '';
if (empty($id)) {
    header("Location: index.php");
    exit();
}

$categories_result = mysqli_query($conn, "SELECT * FROM categories");
$subcategories_result = mysqli_query($conn, "SELECT * FROM subcategories");

$fetch_sql = "SELECT * FROM items WHERE id = ?";
$fetch_stmt = mysqli_prepare($conn, $fetch_sql);
mysqli_stmt_bind_param($fetch_stmt, "i", $id);
mysqli_stmt_execute($fetch_stmt);
$fetch_result = mysqli_stmt_get_result($fetch_stmt);
$item = mysqli_fetch_assoc($fetch_result);

if (!$item) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['update'])) {
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
        $check_sql = "SELECT * FROM items WHERE item_code = ? AND id != ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "si", $item_code, $id);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('This Item Code already exists for another item!');</script>";
        } else {
            $sql = "UPDATE items SET item_code = ?, item_name = ?, category_id = ?, subcategory_id = ?, quantity = ?, unit_price = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param(
                    $stmt, "ssiiidi",
                    $item_code,
                    $item_name,
                    $category_id,
                    $subcategory_id,
                    $quantity,
                    $unit_price,
                    $id
                );

                if(mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('Item updated Successfully!'); window.location.href='index.php';</script>";
                } else {
                    echo "<script>alert('Error updating item!');</script>";
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
    <title>Edit Item</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Item</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Item Code</label>
                <input type="text" name="item_code" class="form-control" value="<?php echo htmlspecialchars($item['item_code'] ?? ''); ?>">
            </div>  
            
            <div class="mb-3">
                <label class="form-label">Item Name</label>
                <input type="text" name="item_name" class="form-control" value="<?php echo htmlspecialchars($item['item_name'] ?? ''); ?>">
            </div>  

            <div class="mb-3">
                <label class="form-label">Item Category</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    <?php 
                    if ($categories_result) {
                        mysqli_data_seek($categories_result, 0);
                        while ($cat = mysqli_fetch_assoc($categories_result)) {
                            $selected = ($item['category_id'] == $cat['id']) ? 'selected' : '';
                            echo "<option value='".$cat['id']."' $selected>".$cat['category_name']."</option>";
                        }
                    }
                    ?>
                </select>
            </div>  

            <div class="mb-3">
                <label class="form-label">Item Sub Category</label>
                <select name="subcategory_id" class="form-select" required>
                    <option value="">Select Sub Category</option>
                    <?php 
                    if ($subcategories_result) {
                        mysqli_data_seek($subcategories_result, 0);
                        while ($sub = mysqli_fetch_assoc($subcategories_result)) {
                            $selected = ($item['subcategory_id'] == $sub['id']) ? 'selected' : '';
                            echo "<option value='".$sub['id']."' $selected>".$sub['subcategory_name']."</option>";
                        }
                    }
                    ?>
                </select>
            </div>  

            <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="<?php echo htmlspecialchars($item['quantity'] ?? ''); ?>">
            </div>  

            <div class="mb-3">
                <label class="form-label">Unit Price</label>
                <input type="number" step="0.01" name="unit_price" class="form-control" value="<?php echo htmlspecialchars($item['unit_price'] ?? ''); ?>">
            </div>  

            <button type="submit" name="update" class="btn btn-primary">Update Item</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
            let itemCode = document.querySelector('[name="item_code"]').value.trim();
            let itemName = document.querySelector('[name="item_name"]').value.trim();
            let categoryId = document.querySelector('[name="category_id"]').value.trim();
            let subcategoryId = document.querySelector('[name="subcategory_id"]').value.trim();
            let quantity = document.querySelector('[name="quantity"]').value.trim();
            let unitPrice = document.querySelector('[name="unit_price"]').value.trim();

            if (itemCode === "" || itemName === "" || categoryId === "" || subcategoryId === "" || quantity === "" || unitPrice === "") {
                alert("Please fill in all required fields!");
                event.preventDefault();
            }
            else if (quantity < 0 || isNaN(quantity)) {
                alert("Please enter a valid quantity!");
                event.preventDefault();
            }
            else if (unitPrice < 0 || isNaN(unitPrice)) {
                alert("Please enter a valid unit price!");
                event.preventDefault();
            }
        });
    </script>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>