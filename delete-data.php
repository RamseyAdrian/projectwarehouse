<?php
include 'db.php';

if (isset($_GET['idk'])) {
    $delete = mysqli_query($conn, "DELETE FROM data_category WHERE category_id = '" . $_GET['idk'] . "' ");
    $delete_barang = mysqli_query($conn, "DELETE * FROM data_product WHERE category_id = '" . $_GET['idk'] . "' ");
    echo '<script>window.location="category-data.php"</script>';
}

if (isset($_GET['idp'])) {
    $produk = mysqli_query($conn, "SELECT product_image FROM data_product WHERE product_id = '" . $_GET['idp'] . "' ");
    $p = mysqli_fetch_object($produk);

    unlink('./produk/' . $p->product_image);

    $delete = mysqli_query($conn, "DELETE FROM data_product WHERE product_id = '" . $_GET['idp'] . "' ");
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

if (isset($_GET['idc'])) {
    $delete = mysqli_query($conn, "DELETE FROM data_cart WHERE product_id = '" . $_GET['idc'] . "' ");
    echo '<script>window.location="user-cart.php"</script>';
}
