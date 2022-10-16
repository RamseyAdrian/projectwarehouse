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

</html>

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
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
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
    $per_page_record = 15;  // Number of entries to show in a page.   
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
            <form action="user-category-search.php" method="GET">
                <input type="text" name="search" placeholder="Cari Kategori">
                <input type="submit" name="cari" value="Cari">
            </form>

        </div>
    </div>

    <!--Category-->
    <div class="section">
        <div class="container">
            <h2>Kategori</h2>
            <div class="box">
                <?php
                $kategori = mysqli_query($conn, "SELECT * FROM data_category ORDER BY category_name   LIMIT $start_from, $per_page_record");
                if (mysqli_num_rows($kategori) > 0) {
                    while ($k = mysqli_fetch_array($kategori)) {
                ?>
                        <a href="user-homepage-product.php?kat=<?php echo $k['category_id'] ?> ">
                            <div class="col-5">
                                <!-- <img src="img/menu_icon.png" width="50px" style="margin-bottom: 5px;"> -->
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


    <center>
        <div class="pagination">
            <?php
            $query = "SELECT COUNT(*) FROM data_category";
            $rs_result = mysqli_query($conn, $query);
            $row = mysqli_fetch_row($rs_result);
            $total_records = $row[0];

            echo "</br>";
            // Number of pages required.   
            $total_pages = ceil($total_records / $per_page_record);
            $pagLink = "";

            if ($page >= 2) {
                echo "<a href='category-product.php?page=" . ($page - 1) . "'>  Prev </a>";
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    $pagLink .= "<a class = 'active' href='category-product.php?page="
                        . $i . "'>" . $i . " </a>";
                } else {
                    $pagLink .= "<a href='category-product.php?page=" . $i . "'>   
                                        " . $i . " </a>";
                }
            };
            echo $pagLink;

            if ($page < $total_pages) {
                echo "<a href='category-product.php?page=" . ($page + 1) . "'>  Next </a>";
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
            window.location.href = 'category-product.php?page=' + page;
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