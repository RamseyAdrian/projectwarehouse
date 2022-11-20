<?php
include 'db.php';

if (isset($_GET['id'])) {
    $update = mysqli_query($conn, "UPDATE data_product
    SET product_status = '0'
    WHERE product_id = '" . $_GET['id'] . "' ");
    echo '<script>window.location="dashboard.php"</script>';
}

if (isset($_GET['ida'])) {
    $update = mysqli_query($conn, "UPDATE data_product
    SET product_status = '1'
    WHERE product_id = '" . $_GET['ida'] . "' ");
    echo '<script>window.location="dashboard.php"</script>';
}

if (isset($_GET['idm'])) {
    $update = mysqli_query($conn, "UPDATE data_product
    SET product_status = '0'
    WHERE product_id = '" . $_GET['idm'] . "' ");
    echo '<script>window.location="more-restock-product.php"</script>';
}

if (isset($_GET['idam'])) {
    $update = mysqli_query($conn, "UPDATE data_product
    SET product_status = '1'
    WHERE product_id = '" . $_GET['idam'] . "' ");
    echo '<script>window.location="more-restock-product.php"</script>';
}
