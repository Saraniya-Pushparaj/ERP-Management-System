<?php
include "../config/database.php";

$id = $_GET['id'] ?? '';
if (empty($id)) {
    header("Location: index.php");
    exit();
}

$fetch_sql = "SELECT * FROM customers WHERE id = ?";
$fetch_stmt = mysqli_prepare($conn, $fetch_sql);
mysqli_stmt_bind_param($fetch_stmt, "i", $id);
mysqli_stmt_execute($fetch_stmt);
$fetch_result = mysqli_stmt_get_result($fetch_stmt);
$customer = mysqli_fetch_assoc($fetch_result);

if (!$customer) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['update'])) {
    $title = $_POST['title'] ?? "";
    $first_name = trim($_POST['first_name'] ?? "");
    $last_name = trim($_POST['last_name'] ?? "");
    $contact_number = trim($_POST['contact_number'] ?? "");
    $district = $_POST['district'] ?? "";

    if (
        empty($title) ||
        empty($first_name) ||
        empty($last_name) ||
        empty($contact_number) ||
        empty($district)
    ) {
        //don't display a PHP message
    }
    elseif (!preg_match('/^[0-9]{10}$/', $contact_number)) {
        //invalid contact number
    }
    else {
        $sql = "UPDATE customers SET title = ?, first_name = ?, last_name = ?, contact_number = ?, district = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "sssssi",
                $title,
                $first_name,
                $last_name,
                $contact_number,
                $district,
                $id
            );

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Customer updated successfully!'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Error updating customer!');</script>";
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
    <title>Edit Customer</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Customer</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <select name="title" class="form-select" required>
                    <option value="">Select Title</option>
                    <option value="Mr" <?php if(($customer['title'] ?? '') == 'Mr') echo 'selected'; ?>>Mr</option>
                    <option value="Mrs" <?php if(($customer['title'] ?? '') == 'Mrs') echo 'selected'; ?>>Mrs</option>
                    <option value="Miss" <?php if(($customer['title'] ?? '') == 'Miss') echo 'selected'; ?>>Miss</option>
                    <option value="Dr" <?php if(($customer['title'] ?? '') == 'Dr') echo 'selected'; ?>>Dr</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($customer['first_name'] ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($customer['last_name'] ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control" value="<?php echo htmlspecialchars($customer['contact_number'] ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">District</label>
                <select name="district" class="form-select" required>
                    <option value="">Select District</option>
                    <?php
                    $districts = ["Colombo", "Gampaha", "Kalutara", "Kandy", "Matale", "Nuwara Eliya", "Galle", "Matara", "Hambantota", "Jaffna", "Killinochchi", "Mannar", "Mullaitivu", "Vavuniya", "Ampara", "Batticaloa", "Trincomalee", "Kurunegala", "Puttalam", "Anuradhapura", "Polannaruwa", "Badulla", "Monaragalla", "Kegalle", "Ratnapura"];
                    foreach ($districts as $dist) {
                        $selected = (($customer['district'] ?? '') == $dist) ? 'selected' : '';
                        echo "<option value='$dist' $selected>$dist</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" name="update" class="btn btn-primary">Update Customer</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>

        </main>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
            let firstName = document.querySelector('[name="first_name"]').value.trim();
            let lastName = document.querySelector('[name="last_name"]').value.trim();
            let contact = document.querySelector('[name="contact_number"]').value.trim();

            if (firstName === "" || lastName === "" || contact === "") {
                alert("Please fill in all required fields!");
                event.preventDefault();
            }
            else if (!/^[0-9]{10}$/.test(contact)) {
                alert("Please enter a valid 10-digit contact number!");
                event.preventDefault();
            }
        });
    </script>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>