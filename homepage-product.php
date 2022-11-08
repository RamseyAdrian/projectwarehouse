<?php
error_reporting(0);
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gudang Ombudsman</title>
    <!--------------------- CSS ------------------------------------->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!--------------------- Font Used ----------------------------->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <!--------------------- Additional CSS ----------------------------->
    <style>
        table {
            border-collapse: collapse;
        }

        .inline {
            display: inline-block;
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

    <!---------------------- header ----------------------------------->
    <header>
        <div class="container">
            <h1><img style="width: 80px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""><a href="index.php"> Gudang Ombudsman</a></h1>
            <ul style="margin-top: 20px ;">
                <li><a href="index.php">Home</a></li>
                <li><a href="category-product.php">Kategori</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
    </header>
    <br>

    <!-----------------------search-------------------------------------->
    <div class="search">
        <div class="container">
            <form action="homepage-product.php" method="GET">
                <input type="text" name="search" placeholder="Cari Produk" value="<?php echo $_GET['search'] ?>">
                <input type="submit" name="cari" value="Cari">
            </form>
        </div>
    </div>

    <!--------------------------items------------------------------------->
    <div class="section">
        <div class="container">
            <h3>Produk</h3>
            <div class="box">
                <?php
                if ($_GET['search'] != '' || $_GET['kat'] != '') {
                    $where = "AND product_name LIKE '%" . $_GET['search'] . "%' AND category_id LIKE '%" . $_GET['kat'] . "%' ";
                }
                $produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_office USING (office_id) WHERE product_status=1 $where
                ORDER BY product_id ");
                if (mysqli_num_rows($produk) > 0) {
                    while ($p = mysqli_fetch_array($produk)) {
                        $namasatuan = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $p['unit_id'] . "' ");
                        $fa_satuan = mysqli_fetch_array($namasatuan);
                ?>
                        <a href="product-detail.php?id=<?php echo $p['product_id'] ?>">
                            <div class="col-4">
                                <center>
                                    <img src="produk/<?php echo $p['product_image'] ?>" alt="">
                                    <br><br>
                                    <h3 class="nama"><?php echo substr($p['product_name'], 0, 20) ?></h3>
                                </center>
                                <!-- <p class="nama"><?php echo $p['office_name'] ?></p> -->

                                <?php
                                if ($p['stock'] == 0) {
                                ?>
                                    <center>
                                        <p style="color: red ;">Stock Habis</p>
                                    </center>
                                <?php
                                } else {
                                ?>
                                    <center>
                                        <p class="nama">Sisa Stock : <?php echo $p['stock'], " ", $fa_satuan['unit_name'] ?></p>
                                    </center>
                                <?php
                                }
                                ?>
                                <center>
                                    <h4 style="color: red ;"><?php echo $p['office_name'] ?></h4>
                                </center>

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



    <!--------------------------- Footer -------------------------------------------------->
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
                            <li><a href="https://ombudsman.go.id/">Ombudsman</a></li>
                            <li><a href="dev-team.php">Dev Team</a></li>
                        </ul>
                    </div>
                    <br>
                </div>
                <p class="copyright">Ombudsman RI © 2022</p>
                <p class="copyright">Made By Divisi HTI & <a href="dev-team.php" target="-blank">Team RJN</a></p>
                <i class="fa-regular fa-cart-shopping"></i>
            </div>
        </footer>
    </div>


</body>

</html>