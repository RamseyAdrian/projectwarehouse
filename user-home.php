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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
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
            <h1><a href="user-home.php">KP Ombudsman</a></h1>
            <ul>
                <li><a href="user-homepage-product.php">Produk</a></li>
                <li><a href="user-cart.php">Keranjang</a></li>
                <li><a href="user-profile.php">Profil Saya</a></li>
                <li><a href="logout.php">Log out</a></li>
            </ul>
        </div>
        <div class="container">

            <h4>Selamat Datang <?php echo $_SESSION['a_global']->user_name ?> di Warehouse Ombudsman</h4>
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

    <!--Category-->
    <div class="section">
        <div class="container">
            <h3>Kategori</h3>
            <div class="box">
                <?php
                $kategori = mysqli_query($conn, "SELECT * FROM data_category ORDER BY category_id DESC");
                if (mysqli_num_rows($kategori) > 0) {
                    while ($k = mysqli_fetch_array($kategori)) {
                ?>
                        <a href="user-homepage-product.php?kat=<?php echo $k['category_id'] ?> ">
                            <div class="col-5">
                                <img src="" width="50px" style="margin-bottom: 5px;">
                                <p><?php echo $k['category_name'] ?></p>
                            </div>
                        </a>
                    <?php }
                } else { ?>
                    <p>Tidak Ada Kategori</p>
                <?php } ?>
            </div>
        </div>
    </div>

    <!--New Product-->
    <div class="section">
        <div class="container">
            <h3>Produk Terbaru</h3>
            <div class="box">
                <?php
                $produk = mysqli_query($conn, "SELECT * FROM data_product LEFT JOIN data_office USING (office_id) WHERE product_status=1 AND office_id = '" . $user_office . "' ORDER BY product_id LIMIT $start_from, $per_page_record ");
                if (mysqli_num_rows($produk) > 0) {
                    while ($p = mysqli_fetch_array($produk)) {
                ?>
                        <a href="user-product-detail.php?id=<?php echo $p['product_id'] ?>">
                            <div class="col-4">
                                <img src="produk/<?php echo $p['product_image'] ?>" alt="">
                                <p class="nama"><?php echo substr($p['product_name'], 0, 30) ?></p>
                                <p class="nama"><?php echo $p['office_name'] ?></p>
                                <p class="nama">Sisa Stok : <?php echo $p['stock'] ?></p>
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

        <!-- <div class="inline">
            <input id="page" type="number" min="1" max="<?php echo $total_pages ?>" placeholder="<?php echo $page . "/" . $total_pages; ?>" required>
            <button onclick="go2Page();">Go</button>
        </div> -->
    </center>


    <script>
        function go2Page() {
            var page = document.getElementById("page").value;
            page = ((page > <?php echo $total_pages; ?>) ? <?php echo $total_pages; ?> : ((page < 1) ? 1 : page));
            window.location.href = 'index.php?page=' + page;
        }
    </script>

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