<?php

include "../config/database.php";

if (isset($_POST['save'])) {

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

        $sql = "INSERT INTO customers
                (title, first_name, last_name, contact_number, district)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,"sssss",
                $title,
                $first_name,
                $last_name,
                $contact_number,
                $district
            );

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Customer added successfully!');</script>";
            } else {
                echo "<script>alert('Error adding customer!');</script>";
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
    <title>Add Customer</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Customer Registration</h2>
            <a href="../index.php" class="btn btn-secondary">Back to Home</a>
        </div>
        <div class="card shadow-sm border p-3">
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <select name="title" class="form-select" required>
                    <option value="" selected disabled>--Select Title--</option>
                    <option value="Mr">Mr</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Miss">Miss</option>
                    <option value="Dr">Dr</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">District</label>
                <select name="district" class="form-select" required>
                    <option value="" selected disabled>--Select District--</option>
                    <option value="Colombo">colombo</option>
                    <option value="Gampaha">Gampaha</option>
                    <option value="Kalutara">Kalutara</option>
                    <option value="Kandy">Kandy</option>
                    <option value="Matale">Matale</option>
                    <option value="Nuwara Eliya">Nuwara Eliya</option>
                    <option value="Galle">Galle</option>
                    <option value="Matara">Matara</option>
                    <option value="Hambantota">Hambontota</option>
                    <option value="Jaffna">Jaffna</option>
                    <option value="Killinochchi">Killinochchi</option>
                    <option value="Mannar">Mannar</option>
                    <option value="Mullaitivu">Mullaitivu</option>
                    <option value="Vavuniya">Vavuniya</option>
                    <option value="Ampara">Ampara</option>
                    <option value="Batticaloa">Batticaloa</option>
                    <option value="Trincomalee">Trincomalee</option>
                    <option value="Kurunegala">Kurunegala</option>
                    <option value="Puttalam">Puttalam</option>
                    <option value="Anuradhapura">Anuradhapura</option>
                    <option value="Polannaruwa">Polannaruwa</option>
                    <option value="Badulla">Badulla</option>
                    <option value="Monaragalla">Monaragalla</option>
                    <option value="Kegalle">Kegalle</option>
                    <option value="Ratnapura">Ratnapura</option>
                </select>
            </div>

            <button type="submit" name="save" class="btn btn-primary">Save Customer</button>
            <button type="reset" class="btn btn-secondary">Clear</button>

        </form>
        </div>
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