<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | KP Ombudsman</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <script src="js/sweetalert.min.js"></script>
</head>

<body id="bg-login">

    <div class="box-login">
        <h2>Login</h2>
        <form action="" method="POST">
            <input type="text" name="user" placeholder="Username" class="input-control">
            <input type="password" name="pass" placeholder="Password" class="input-control">
            <h4>If you're a user, <a href="login.php">Login here</a></h4><br>
            <input type="submit" name="submit" value="Login" class="btn"><br><br>
            <h4><a href="index.php">Home Page</a></h4>
        </form>
        <?php
        if (isset($_POST['submit'])) {
            session_start();
            include 'db.php';

            $user = mysqli_real_escape_string($conn, $_POST['user']);
            $pass = mysqli_real_escape_string($conn, $_POST['pass']);

            $cek_admin = mysqli_query($conn, "SELECT * FROM data_admin WHERE admin_username = '" . $user . "' AND admin_password = '" . MD5($pass) . "'");
            $cek_super = mysqli_query($conn, "SELECT * FROM data_superadmin WHERE super_username = '" . $user . "' AND super_password = '" . MD5($pass) . "' ");
            if (mysqli_num_rows($cek_admin) > 0) {
                $d = mysqli_fetch_object($cek_admin);
                $_SESSION['status_login'] = true;
                $_SESSION['a_global'] = $d;
                $_SESSION['id'] = $d->admin_id;

                echo '<script>window.location="dashboard.php"</script>';
                unset($_SESSION[$pass]);
            } else if (mysqli_num_rows($cek_super) > 0) {
                $s = mysqli_fetch_object($cek_super);
                $_SESSION['status_login'] = true;
                $_SESSION['a_global'] = $s;
                $_SESSION['id'] = $s->super_id;

                echo '<script>window.location="dashboard-super.php"</script>';
                unset($_SESSION[$pass]);
            } else {
                echo '<script>swal("Username atau Password Salah !");</script>';
            }
        }
        ?>
    </div>
</body>

</html>