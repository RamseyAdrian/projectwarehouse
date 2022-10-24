<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Gudang Ombudsman</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script>
        function showPass() {
            var getElement = document.getElementById("pass-field");
            if (getElement.type === "password") {
                getElement.type = "text";
            } else {
                getElement.type = "password";
            }
        }
    </script>
</head>

<body id="bg-login">
    <div class="card-container">
        <form id="login-form" method="post">
            <div class="logo-ombudsman">
                <img src="img/logo-ombudsman.png" alt="">
            </div>

            <h3 style="text-align: center ;">LOGIN</h3>

            <div class="input-container">
                <div class="text-input">
                    <label for="uname"><b>Username</b></label>
                </div>
                <div class="input-username">
                    <input type="text" id="login-field" name="uname" required>
                </div>
            </div>

            <div class="input-container">
                <div class="text-input">
                    <label for="psw"><b>Password</b></label>
                </div>
                <div class="input-password">
                    <input type="password" id="pass-field" name="psw" required>
                </div>
                <div class="text-input">
                    <input type="checkbox" onclick="showPass()"> Show Password
                </div>
            </div>



            <div class="captcha">

                <div class="g-recaptcha" data-sitekey="6LdK0dchAAAAAKd12AE08TRkbZh6hH5nw529W1Ny"></div>
                <br />
                <button type="submit" name="login" value="Login">Login</button>

            </div>
        </form>

        <?php
        if (isset($_POST['login'])) {
            session_start();
            include 'db.php';

            $user = mysqli_real_escape_string($conn, $_POST['uname']);
            $pass = mysqli_real_escape_string($conn, $_POST['psw']);

            $cek_user = mysqli_query($conn, "SELECT * FROM data_user WHERE user_username = '" . $user . "' AND user_password = '" . MD5($pass) . "'");
            $cek_admin = mysqli_query($conn, "SELECT * FROM data_admin WHERE admin_username = '" . $user . "' AND admin_password = '" . MD5($pass) . "'");
            $cek_super = mysqli_query($conn, "SELECT * FROM data_superadmin WHERE super_username = '" . $user . "' AND super_password = '" . MD5($pass) . "' ");

            if (mysqli_num_rows($cek_user) > 0) {

                if (isset($_POST['g-recaptcha-response'])) {
                    $secretKey = "6LdK0dchAAAAAMjRcojkod3tQKZqfsizXZbnuQ3E";
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $response = $_POST['g-recaptcha-response'];
                    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$response&remoteip=$ip";

                    $fire = file_get_contents($url);
                    $data = json_decode($fire);

                    $fo_user = mysqli_fetch_object($cek_user);


                    if ($data->success == true) {
                        $_SESSION['status_login'] = true;
                        $_SESSION['role_login'] = 'user';
                        $_SESSION['a_global'] = $fo_user;
                        $_SESSION['id'] = $fo_user->user_id;
                        echo '<script>window.location="user-home.php"</script>';
                        unset($_SESSION[$pass]);
                    } else {
                        echo '<script>swal("Please fill the captcha !")</script>';
                    }
                } else {
                    echo "Captcha Error!";
                }
            } else if (mysqli_num_rows($cek_admin) > 0) {
                if (isset($_POST['g-recaptcha-response'])) {
                    $secretKey = "6LdK0dchAAAAAMjRcojkod3tQKZqfsizXZbnuQ3E";
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $response = $_POST['g-recaptcha-response'];
                    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$response&remoteip=$ip";

                    $fire = file_get_contents($url);
                    $data = json_decode($fire);

                    $fo_admin = mysqli_fetch_object($cek_admin);


                    if ($data->success == true) {
                        $_SESSION['status_login'] = true;
                        $_SESSION['role_login'] = 'admin';
                        $_SESSION['a_global'] = $fo_admin;
                        $_SESSION['id'] = $fo_admin->admin_id;
                        echo '<script>window.location="dashboard.php"</script>';
                        unset($_SESSION[$pass]);
                    } else {
                        echo '<script>swal("Please fill the captcha !")</script>';
                    }
                } else {
                    echo "Captcha Error!";
                }
            } else if (mysqli_num_rows($cek_super) > 0) {
                if (isset($_POST['g-recaptcha-response'])) {
                    $secretKey = "6LdK0dchAAAAAMjRcojkod3tQKZqfsizXZbnuQ3E";
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $response = $_POST['g-recaptcha-response'];
                    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$response&remoteip=$ip";

                    $fire = file_get_contents($url);
                    $data = json_decode($fire);

                    $fo_super = mysqli_fetch_object($cek_super);


                    if ($data->success == true) {
                        $_SESSION['status_login'] = true;
                        $_SESSION['role_login'] = 'super';
                        $_SESSION['a_global'] = $fo_super;
                        $_SESSION['id'] = $fo_super->super_admin_id;
                        echo '<script>window.location="dashboard.php"</script>';
                        unset($_SESSION[$pass]);
                    } else {
                        echo '<script>swal("Please fill the captcha !")</script>';
                    }
                } else {
                    echo "Captcha Error!";
                }
            } else {
                echo '<script>swal("Username atau Password Salah !");</script>';
            }
        };
        ?>

    </div>
</body>



</html>