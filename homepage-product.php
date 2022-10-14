<?php
error_reporting(0);
include 'db.php';
$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM data_admin WHERE admin_id = 1");
$a = mysqli_fetch_object($kontak);

$qd = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = 11");
$fo = mysqli_fetch_object($qd);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KP Ombudsman</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <style>
        table {
            border-collapse: collapse;
        }

        .inline {
            display: inline-block;
            /* float: right; */
            margin: 20px 0px;
        }

        input,
        button {
            height: 34px;
        }

        .pagination {
            display: inline-block;
        }

        .pagination a {
            font-weight: bold;
            font-size: 18px;
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid black;
        }

        .pagination a.active {
            background-color: pink;
        }

        .pagination a:hover:not(.active) {
            background-color: skyblue;
        }
    </style>
</head>

<body>
    <?php
    $per_page_record = 4;  // Number of entries to show in a page.   
    // Look for a GET variable page if not found default is 1.        
    if (isset($_GET["page"])) {
        $page  = $_GET["page"];
    } else {
        $page = 1;
    }

    $start_from = ($page - 1) * $per_page_record;
    ?>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="index.php">KP Ombudsman</a></h1>
            <ul>
                <li><a href="homepage-product.php">Produk</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
    </header>

    <!--search-->
    <div class="search">
        <div class="container">
            <form action="homepage-product.php" method="GET">
                <input type="text" name="search" placeholder="cari produk" value="<?php echo $_GET['search'] ?>">
                <input type="submit" name="cari" value="Cari">
            </form>
        </div>
    </div>

    <!-- New Product -->

    <div class="section">
        <div class="container">
            <h3>Produk</h3>
            <div class="box">
                <?php
                if ($_GET['search'] != '' || $_GET['kat'] != '') {
                    $where = "AND product_name LIKE '%" . $_GET['search'] . "%' AND category_id LIKE '%" . $_GET['kat'] . "%' ";
                }
                $produk = mysqli_query($conn, "SELECT * FROM data_product WHERE product_status=1 $where
                ORDER BY product_id ");
                if (mysqli_num_rows($produk) > 0) {
                    while ($p = mysqli_fetch_array($produk)) {
                ?>
                        <a href="product-detail.php?id=<?php echo $p['product_id'] ?>">
                            <div class="col-4">
                                <img src="produk/<?php echo $p['product_image'] ?>" alt="">
                                <p class="nama"><?php echo substr($p['product_name'], 0, 30) ?></p>
                                <p class="harga">Rp<?php echo $p['product_price'] ?></p>
                            </div>
                        </a>
                    <?php }
                } else { ?>
                    <p>Tidak Ada Produk</p>
                <?php } ?>
            </div>
        </div>
    </div>



    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <h4>Alamat Kantor Ombudsman RI</h4>
            <p><?php echo $fo->office_address ?></p>

            <h4>Email</h4>
            <p><?php echo $fo->office_email ?></p>

            <h4>Nomor Telfon</h4>
            <p><?php echo $fo->office_telp ?></p>
            <small>Copyright &copy; 2022 - KP Ombudsman</small>
        </div>
    </div>


</body>

</html>