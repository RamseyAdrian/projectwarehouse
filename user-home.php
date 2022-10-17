<?php
session_start();
include 'db.php';
$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM data_admin WHERE admin_id = 1");
$a = mysqli_fetch_object($kontak);
if ($_SESSION['role_login'] != 'user') {

    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$qd = mysqli_query($conn, "SELECT * FROM data_office WHERE office_id = 11");
$fo = mysqli_fetch_object($qd);

$user_office = $_SESSION['a_global']->office_id;
$user_id = $_SESSION['a_global']->user_id;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ombudsman</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        table {
            border-collapse: collapse;
        }

        .inline {
            display: inline-block;
            /* float: right; */
            margin: 20px 0px;
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
    $per_page_record = 12;  // Number of entries to show in a page.   
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

            <h1><img style="width: 80px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""><a href="user-home.php"> Gudang Ombudsman</a></h1>
            <ul style="margin-top: 20px ;">
                <?php
                $isi = 0;
                $keranjang = mysqli_query($conn, "SELECT * FROM data_cart WHERE user_id = '" . $user_id . "' AND office_id = '" . $user_office . "' ");
                if (mysqli_num_rows($keranjang) > 0) {
                    while ($fetch_keranjang = mysqli_fetch_array($keranjang)) {
                        $isi++;
                    }
                }
                ?>
                <li><a href="user-home.php">Home</a></li>
                <li><a href="user-category-product.php">Kategori</a></li>
                <li><a href="user-cart.php"><img style="width:16px ;" src="img/cart.png" alt="">(<?php echo $isi; ?>)</a></li>
                <li><a href="user-order.php">Transaksi</a></li>
                <li><a href="user-profile.php">Profil Saya</a></li>
                <li><a href="logout.php">Log out</a></li>
            </ul>
        </div>
    </header>



    <!--search-->
    <div class="search">
        <div class="container">
            <form action="user-homepage-product.php" method="GET">
                <input type="text" name="search" placeholder="cari produk">
                <input type="submit" name="cari" value="Cari">
            </form>
        </div>
    </div>

    <!--New Product-->
    <div class="section">
        <div class="container">
            <div class="box">
                <?php
                $produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_office USING (office_id) WHERE product_status=1 AND office_id = '" . $user_office . "' ORDER BY product_id LIMIT $start_from, $per_page_record ");
                if (mysqli_num_rows($produk) > 0) {
                    while ($p = mysqli_fetch_array($produk)) {
                        $namasatuan = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $p['unit_id'] . "' ");
                        $fa_satuan = mysqli_fetch_array($namasatuan);
                ?>
                        <a href="user-product-detail.php?id=<?php echo $p['product_id'] ?>">
                            <div class="col-4">
                                <center>
                                    <img src="produk/<?php echo $p['product_image'] ?>" alt="">
                                    <br><br>
                                    <h3 class="nama"><?php echo substr($p['product_name'], 0, 30) ?></h3>
                                </center>
                                <!-- <p class="nama"><?php echo $p['office_name'] ?></p> -->

                                <?php
                                if ($p['stock'] == 0) {
                                ?>
                                    <center>
                                        <p style="color: red ;">Stock Habis, Hubungi Admin untuk Restock</p>
                                    </center>
                                <?php
                                } else {
                                ?>
                                    <center>
                                        <p class="nama">Sisa Stok : <?php echo $p['stock'], " ", $fa_satuan['unit_name'] ?></p>
                                    </center>
                                <?php
                                }
                                ?>

                                <!-- <p class="harga">Rp<?php echo $p['product_price'] ?></p> -->
                            </div>
                        </a>
                    <?php }
                } else { ?>
                    <p>Tidak Ada Produk</p>
                <?php } ?>
            </div>
        </div>
    </div>

    <center>
        <div class="pagination">
            <?php
            $query = "SELECT COUNT(*) FROM data_product";
            $rs_result = mysqli_query($conn, $query);
            $row = mysqli_fetch_row($rs_result);
            $total_records = $row[0];

            echo "</br>";
            // Number of pages required.   
            $total_pages = ceil($total_records / $per_page_record);
            $pagLink = "";

            if ($page >= 2) {
                echo "<a href='user-home.php?page=" . ($page - 1) . "'>  Prev </a>";
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    $pagLink .= "<a class = 'active' href='user-home.php?page="
                        . $i . "'>" . $i . " </a>";
                } else {
                    $pagLink .= "<a href='user-home.php?page=" . $i . "'>   
                                        " . $i . " </a>";
                }
            };
            echo $pagLink;

            if ($page < $total_pages) {
                echo "<a href='user-home.php?page=" . ($page + 1) . "'>  Next </a>";
            }
            ?>
        </div><br><br><br><br>

    </center>


    <script>
        function go2Page() {
            var page = document.getElementById("page").value;
            page = ((page > <?php echo $total_pages; ?>) ? <?php echo $total_pages; ?> : ((page < 1) ? 1 : page));
            window.location.href = 'user-home.php?page=' + page;
        }
    </script>

    <!-- Footer -->
    <div class="footer-dark">
        <footer>
            <div class="container">
                <div class="row" style="display: flex ;">
                    <div class="col-md-6 item text" style="margin-right: 90px ;">
                        <h3>Ombudsman RI</h3>
                        <p>Kantor Pusat <br>
                            Jl. HR. Rasuna Said Kav. C-19 Kuningan, Jakarta Selatan 12920</p>
                    </div>
                    <div class="col-sm-6 col-md-3 item" style="margin-right: 90px ;">
                        <h3>Kontak</h3>
                        <ul>
                            <li><a href="#">No Telfon : (021) 2251 3737</a></li>
                            <li><a href="#">Fax : (021) 5296 0907 / 5296 0908</a></li>
                            <li><a href="#">Email : humas@ombudsman.go.id</a></li>
                        </ul>
                    </div>
                    <br>
                    <div class="col-sm-6 col-md-3 item" style="margin-right: 90px ;">
                        <h3>About</h3>
                        <ul>
                            <li><a href="#">Company</a></li>
                            <li><a href="#">Team</a></li>
                        </ul>
                    </div>
                    <br>

                </div>
                <p class="copyright">Ombudsman RI © 2022</p>
                <p class="copyright">Made By Divisi HTI & Team RJN</p>
            </div>
        </footer>
    </div>


</body>

</html>