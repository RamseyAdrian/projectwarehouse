<?php
include 'db.php';
// Include the database connection file 
require_once 'cart/dbConnect.php';

// Initialize shopping cart class 
include_once 'cart/Cart.class.php';
$cart = new Cart;

// Fetch products from the database 
$sqlQ = "SELECT * FROM data_product";
$stmt = $db->prepare($sqlQ);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>PHP Shopping Cart</title>
    <meta charset="utf-8">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


    <!-- Custom style -->
    <link href="css/style.css" rel="stylesheet">

</head>

<body>
    <div class="container">
        <h1>PRODUCTS</h1>

        <!-- Cart basket -->
        <div class="cart-view">
            <a href="viewCart.php" title="View Cart"><i class="icart"></i> (<?php echo ($cart->total_items() > 0) ? $cart->total_items() . ' Items' : 0; ?>)</a>
        </div>

        <!-- Product list -->
        <div class="row col-lg-12">
            <?php
            $produk = mysqli_query($conn, "SELECT * FROM data_product WHERE product_status=1 ORDER BY product_id");
            if (mysqli_num_rows($produk) > 0) {
                while ($row = mysqli_fetch_array($produk)) {
                    $proImg = !empty($row["product_image"]) ? 'produk/' . $row["product_image"] : 'images/demo-img.png';
            ?>
                    <div class="card" style="width: 18rem;">
                        <img src="<?php echo $proImg; ?>" style="width: 50px ; height: 50px;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row["product_name"]; ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">Price: <?php echo CURRENCY_SYMBOL . $row["product_price"] . ' ' . CURRENCY; ?></h6>
                            <p class="card-text"><?php echo $row["product_description"]; ?></p>
                            <a href="cart/cartAction.php?action=addToCart&id=<?php echo $row["product_id"]; ?>" class="btn btn-primary">Add to Cart</a>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <p>Product(s) not found.....</p>
            <?php } ?>
        </div>
    </div>
</body>

</html>