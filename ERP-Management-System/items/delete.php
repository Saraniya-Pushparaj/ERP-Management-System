<?php
include "../config/database.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM items WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);

        try {
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Item deleted successfully!');
                        window.location.href = 'index.php';</script>";
            }
        } catch (mysqli_sql_exception $e) {
            echo "<script>alert('Cannot delete this item! It is already used in existing invoices.');
                    window.location.href = 'index.php';</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Database query preparation failed!');
                window.location.href = 'index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit();
}
?>