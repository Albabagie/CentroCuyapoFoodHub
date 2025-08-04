<?php
require_once '../connection.php';


if (isset($_POST['verification'])) {
    $email = $_POST['email'];
    $email_number = $_POST['number_verify'];

    $sql_verification = "SELECT email, email_ver_num FROM users WHERE email = '$email' AND email_ver_num = '$email_number' AND email_verified = 0";
    $result = $conn->query($sql_verification);

    if ($result->num_rows > 0) {
        $update_ver = "UPDATE users SET email_verified = 1 WHERE email = '$email'";
        $result_upt = $conn->query($update_ver);
        if ($result_upt === TRUE) {
            echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Account Verification Successfully!",
                    text: "Please Login your account to continue.",
                    icon: "success"
                }).then(function() {
                    setTimeout(function() {
                        window.location.href = "index.php"; 
                    }, 2); 
                });
            });
        </script>';
            // header('Location: index.php');
        } else {
            echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Wrong Verification Number!",
                    text: "Please Check your Email for verification.",
                    icon: "error"
                });
            });
        </script>';
            $result->close();
        }
    } else {
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: "Email Doesn\'t Exist! or Account is Verified Already",
                text: "Please Check your Info!",
                icon: "info"
            });
        });
    </script>';
        $result->close();
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Food Hub | Centro Cuyapo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.min.css">
    <!-- <link rel="stylesheet" href="./sweetalert2.min.css"> -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
    <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <link rel="stylesheet" href="../customize.css">
    <link rel="stylesheet" href="../css/plugin.min.css">
    <link rel="stylesheet" href="../unicons.iconscout.com/release/v4.0.0/css/line.css">


    <!-- <link rel="icon" type="image/png" sizes="16x16" href="img/favicon.png"> -->
    <!-- <script src="./sweetalert2.all.min.js"></script> -->



</head>

<body>

    <div class="w-100">
        <a href="../" class="btn text-light my-md-4 mx-md-4 fs-5"><i
                class="uil uil-arrow-left ml-sm-2 fs-5"></i>Home</a>
    </div>
    <div class="admin">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xxl-3 col-xl-4 col-md-6 col-sm-8">
                    <div class="edit-profile mt-lg-5">
                        <div class="container">
                            <div class="heading">Verification Account</div>
                            <form class="form" method="POST">
                                <label for="email" class="form-control mt-md-2">Email:</label>
                                <input placeholder="Enter Email Registered " id="email" name="email" type="email"
                                    class="input" required />
                                <label for="ver_num" class="form-control mt-md-4">Verification Number:</label>
                                <input placeholder="Enter Verification Number" id="ver_num" name="number_verify"
                                    type="number" class="input" required />
                                <input value="Verify Account" type="submit" class="login-button" name="verification" />
                            </form>


                            <div class="admin-topbar text-center mt-md-4 ">
                                <p class="mb-0">

                                    <a href="index.php" class="login-button">
                                        Sign In
                                    </a>

                                </p>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.all.min.js"></script>

    <script src="js/plugins.min.js"></script>
    <script src="js/script.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.all.min.js"></script>
</body>

</html>