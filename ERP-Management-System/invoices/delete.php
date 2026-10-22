<?php
include "../config/database.php";

if (isset($_GET['id'])) {
    $invoice_id = $_GET['id'];

    $sql_item = "DELETE FROM invoice_items WHERE invoice_id = ?";
    $stmt1 = mysqli_prepare($conn, $sql_item);

    if ($stmt1) {
        mysqli_stmt_bind_param($stmt1, "i", $invoice_id);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_close($stmt1);
    }

    $sql_inv = "DELETE FROM invoices WHERE id = ?";
    $stmt2 = mysqli_prepare($conn, $sql_inv);

    if ($stmt2) {
        mysqli_stmt_bind_param($stmt2, "i", $invoice_id);

        if (mysqli_stmt_execute($stmt2)) {
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Error deleting invoice!'); window.location.href='index.php';</script>";
        }

        mysqli_stmt_close($stmt2);
    }
} else {
    header("Location: index.php");
    exit();
}
?>