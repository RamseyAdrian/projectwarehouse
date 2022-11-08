<?php
session_start();
include 'db.php';

if ($_SESSION['role_login'] == 'user') {
    echo '<script>window.location = "logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$kantor_admin = $_SESSION['a_global']->office_id;


$update_stocking = mysqli_query($conn, "UPDATE stocking_item SET reset_status = '1' WHERE office_id = '" . $kantor_admin . "' ");

$update_transaction_history = mysqli_query($conn, "UPDATE transaction_history SET reset_status = '1' WHERE office_id = '" . $kantor_admin . "' ");

$update_initial_stock = mysqli_query($conn, "UPDATE data_product SET initial_stock = stock WHERE office_id = '" . $kantor_admin . "' ");

echo '<script>window.location="in-out-product.php"</script>';
