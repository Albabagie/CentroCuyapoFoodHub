<?php
// Start the session
session_start();

// Include the connection file
include('../connection.php');

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
      // Store user ID in session

      $userid =  $row['user_id'];

      $_SESSION['user_id'] = $row['user_id'];

      $_SESSION['category'] = $row['category'];


      if ($row['account_type'] == 'employee') {
          
        $sql = "SELECT * FROM employee WHERE user_id = $userid  AND employee_type = 'stall' AND employee_status = 'Active'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
              header('Location: ../stalls/index.php');

        } else{
        header('Location: ../employee/index.php');

        }

        exit();
      } elseif ($row['account_type'] == 'customer') {
        if ($row['email_verified'] == 1) {
          header('Location: ../customer/index.php');
        } else {
          echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire({
                      title: "Account Not Verified",
                      text: "Please verify your email address.",
                      icon: "info"
                  });
              });
          </script>';
        }
      } elseif ($row['account_type'] == 'admin') {

        header('Location: ../admin/index.php');
      } 
       
          
    }
  } else {
    echo '<script>
      document.addEventListener("DOMContentLoaded", function() {
          Swal.fire({
              title: "Wrong Email and Password",
              text: "Please Check your Email and Password.",
              icon: "info"
          });
      });
  </script>';
  }
}

// $conn->close();


$term = $conn->query("SELECT * FROM policy_term ORDER BY date_updated DESC");
if( $term ){
  $res_term = $term->fetch_assoc();
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
  <main class="main-content">

    <div class="area position-absolute" style="z-index:-9999;">
      <ul class="circles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
      </ul>
    </div>
    <div class="w-100">
      <a href="../" class="btn text-light my-md-4 mx-md-4 fs-5"><i class="uil uil-arrow-left ml-sm-2 fs-5"></i>Home</a>
    </div>
    <div class="admin">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-xxl-3 col-xl-4 col-md-6 col-sm-8">
            <div class="edit-profile mt-lg-5">
              <div class="container">
                <div class="heading">Sign In</div>
                <form class="form" method="POST">
                  <input placeholder="E-mail" id="email" name="email" type="text" class="input" required="" />
                  <input placeholder="Password" id="password" name="password" type="password" class="input" required="" />
                  <!-- <span class="forgot-password text-dark"><a href="#">Forgot Password ?</a></span> -->
                  <input value="Sign In" type="submit" class="login-button" name="login" />
                </form>

                <span class="agreement"><a href="#" type="button" data-bs-toggle="modal" data-bs-target="#agreement">Learn user licence agreement</a></span>

                <div class="admin-topbar text-center mt-md-4 d-flex justify-content-around">
                  <p class="mb-0">

                    <a href="signup.php" class="login-button">
                      Sign up
                    </a>

                  </p>
                  <p class="mb-0">

                    <a href="verification.php" class="login-button">
                      Verify Account
                    </a>

                  </p>
                </div>
              </div>


            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="agreement" tabindex="-1" role="dialog" aria-labelledby="terms" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="terms"> Terms of Service and Privacy Policy</h5>
            <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          
          <div class="modal-body">
           <p>
         <?php
         if($res_term){
          echo "<pre>" . htmlspecialchars($res_term['terms_policies']) . "</pre>";
         }
         ?>
           </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" style="color:white" data-bs-dismiss="modal">I agee</button>

          </div>
        </div>
      </div>
    </div>
  </main>
  <div id="overlayer">
    <div class="loader-overlay">
      <div class="dm-spin-dots spin-lg">
        <span class="spin-dot badge-dot dot-secondary"></span>
        <span class="spin-dot badge-dot dot-secondary"></span>
        <span class="spin-dot badge-dot dot-secondary"></span>
        <span class="spin-dot badge-dot dot-secondary"></span>
      </div>
    </div>
  </div>
  <div class="enable-dark-mode dark-trigger">
    <!-- <ul>
      <li> -->
    <!-- <a href="#"> -->
    <!-- <i class="uil uil-moon"></i> -->
    <!-- </a>
      </li>
    </ul> -->
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.all.min.js"></script>
  <script src="../js/plugins.min.js"></script>
  <script src="../js/script.min.js"></script>
</body>


</html>