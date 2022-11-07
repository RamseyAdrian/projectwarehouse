<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">

</html>

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
    <!--------------------- Font Awesome ----------------------------->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    <?php
    $per_page_record = 12; // Jumlah Item Display pada page        
    if (isset($_GET["page"])) {
        $page  = $_GET["page"];
    } else {
        $page = 1;
    }
    $start_from = ($page - 1) * $per_page_record;
    ?>

    <!---------------------- header ----------------------------------->

    <header>
        <div class="container" id="navLinks">
            <h1><img style="width: 80px ; margin-bottom :-10px ;" src="img/logo-ombudsman2.png" alt=""><a href="index.php"> Gudang Ombudsman</a></h1>
            <!-- <i class="fa fa-times fa-2x" onclick="hideMenu()"></i> -->
            <ul style="margin-top: 20px ;">
                <li><a href="index.php">Home</a></li>
                <li><a href="category-product.php">Kategori</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
        <!-- <i class="fa fa-bars fa-2x" onclick="showMenu()"></i> -->
    </header>

    <!-----------------------search-------------------------------------->

    <div class="section">
        <div class="search">
            <div class="container">
                <form action="homepage-product.php" method="GET">
                    <input type="text" name="search" placeholder="cari produk">
                    <input type="submit" name="cari" value="Cari">
                </form>

            </div>
        </div>
    </div>


    <!--------------------------items------------------------------------->

    <div class="section">
        <div class="container">
            <div class="box-items">
                <?php
                $produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_office USING (office_id) WHERE product_status=1 ORDER BY product_id LIMIT $start_from, $per_page_record ");
                if (mysqli_num_rows($produk) > 0) {
                    while ($fetch_produk = mysqli_fetch_array($produk)) {
                        $namasatuan = mysqli_query($conn, "SELECT * FROM data_unit WHERE unit_id = '" . $fetch_produk['unit_id'] . "' ");
                        $fa_satuan = mysqli_fetch_array($namasatuan);
                ?>
                        <a href="product-detail.php?id=<?php echo $fetch_produk['product_id'] ?>">
                            <div class="col-4">
                                <center>
                                    <img src="produk/<?php echo $fetch_produk['product_image'] ?>" alt="">
                                    <br><br>
                                    <h3 class="nama"><?php echo substr($fetch_produk['product_name'], 0, 20) ?></h3>
                                </center>
                                <?php
                                if ($fetch_produk['stock'] == 0) {
                                ?>
                                    <center>
                                        <p style="color: red ;">Stock Habis</p>
                                    </center>
                                <?php
                                } else {
                                ?>
                                    <center>
                                        <p class="nama">Sisa Stok : <?php echo $fetch_produk['stock'], " ", $fa_satuan['unit_name'] ?></p>
                                    </center>
                                <?php
                                }
                                ?>
                                <center>
                                    <h4 style="color: red ;"><?php echo $fetch_produk['office_name'] ?></h4>
                                </center>
                            </div>
                        </a>
                    <?php }
                } else { ?>
                    <p>Tidak Ada Produk</p>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-------------------------------- Pagination ------------------------------------>

    <center>
        <div class="pagination">
            <?php
            $query = "SELECT COUNT(*) FROM data_product";
            $rs_result = mysqli_query($conn, $query);
            $row = mysqli_fetch_row($rs_result);
            $total_records = $row[0];

            echo "</br>";
            $total_pages = ceil($total_records / $per_page_record);
            $pagLink = "";

            if ($page >= 2) {
                echo "<a href='index.php?page=" . ($page - 1) . "'>  Prev </a>";
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    $pagLink .= "<a class = 'active' href='index.php?page="
                        . $i . "'>" . $i . " </a>";
                } else {
                    $pagLink .= "<a href='index.php?page=" . $i . "'>   
                                        " . $i . " </a>";
                }
            };
            echo $pagLink;

            if ($page < $total_pages) {
                echo "<a href='index.php?page=" . ($page + 1) . "'>  Next </a>";
            }
            ?>
        </div><br><br><br><br>
    </center>


    <script>
        function go2Page() {
            var page = document.getElementById("page").value;
            page = ((page > <?php echo $total_pages; ?>) ? <?php echo $total_pages; ?> : ((page < 1) ? 1 : page));
            window.location.href = 'index.php?page=' + page;
        }
    </script>

</body>

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

<!-----Javascript for Toggle Menu------>
<script>
    var navLinks = document.getElementById("navLinks");

    function showMenu() {
        navLinks.style.right = "0";
    }

    function hideMenu() {
        navLinks.style.right = "-200px";
    }
</script>

</html>