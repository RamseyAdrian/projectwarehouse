<?php
include 'db.php';

if (isset($_GET['idk'])) {
    $delete_barang = mysqli_query($conn, "DELETE FROM data_product WHERE category_id = '" . $_GET['idk'] . "' ");
    $delete = mysqli_query($conn, "DELETE FROM data_category WHERE category_id = '" . $_GET['idk'] . "' ");
    echo '<script>window.location="category-data.php"</script>';
}

if (isset($_GET['idp'])) {
    $produk = mysqli_query($conn, "SELECT product_image FROM data_product WHERE product_id = '" . $_GET['idp'] . "' AND office_id = '" . $_GET['idoffice'] . "' ");
    $p = mysqli_fetch_object($produk);

    unlink('./produk/' . $p->product_image);

    $delete = mysqli_query($conn, "DELETE FROM data_product WHERE product_id = '" . $_GET['idp'] . "' AND office_id = '" . $_GET['idoffice'] . "' ");
    echo '<script>window.location="product-data.php"</script>';
}

if (isset($_GET['ida'])) {
    $delete = mysqli_query($conn, "DELETE FROM data_admin WHERE admin_id = '" . $_GET['ida'] . "' ");
    echo '<script>window.location="admin-data.php"</script>';
}

if (isset($_GET['idu'])) {
    $delete = mysqli_query($conn, "DELETE FROM data_user WHERE user_id = '" . $_GET['idu'] . "' ");
    echo '<script>window.location="user-data.php"</script>';
}

if (isset($_GET['ido'])) {
    $delete = mysqli_query($conn, "DELETE FROM data_office WHERE office_id = '" . $_GET['ido'] . "' ");
    echo '<script>window.location="office-data.php"</script>';
}

if (isset($_GET['idc']) && isset($_GET['iduser'])) {
    $delete = mysqli_query($conn, "DELETE FROM data_cart WHERE product_id = '" . $_GET['idc'] . "' AND user_id = '" . $_GET['iduser'] . "' ");

    echo '<script>window.location="user-cart.php"</script>';
}

if (isset($_GET['ids'])) {
    $delete = mysqli_query($conn, "DELETE FROM data_unit WHERE unit_id = '" . $_GET['ids'] . "' ");
    echo '<script>window.location="unit-data.php"</script>';
}

if (isset($_GET['idpd'])) {
    $delete = mysqli_query($conn, "DELETE FROM data_transaction WHERE order_id = '" . $_GET['idpd'] . "' ");
    echo '<script>window.location="pickup-product.php"</script>';
}
