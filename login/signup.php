<?php

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$rootUrl = $protocol . $host;


include('../connection.php');

require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

use phpmailer\PHPMailer\PHPMailer;

class EmailSender
{
  private $mail;

  public function __construct()
  {
    $this->mail = new PHPMailer(true);
  }

  public function sendVerificationEmail($email, $name, $link, $verification_code)
  {
    try {
      //Server settings
      $this->mail->isSMTP();
      $this->mail->Host = 'smtp.gmail.com'; // SMTP server
      $this->mail->SMTPAuth = true;
      $this->mail->Username = 'centrocuyapofoodhuba@gmail.com'; // SMTP username
      $this->mail->Password = 'zspibwximocjqrwb'; // SMTP password
      $this->mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
      $this->mail->Port = 587; // TCP port to connect to

      //Recipients
      $this->mail->setFrom('centrocuyapofoodhuba@gmail.com', 'Food Hub | Centro Cuyapo');
      $this->mail->addAddress($email); // Add a recipient

      // Content
      $this->mail->isHTML(true);
      $this->mail->Subject = 'Email Verification Code';
      $this->mail->Body = "<div style='width: 700px; border-radius: 30px; font-style:normal; font-family:'Open Sans', sans-serif;'>
    <div style='margin: 20px 10px;'>
        <div style='padding: 10px 5px; background-color: orange; color: white; border:1px solid rgba(245, 245, 245, 0.699); text-align:center; border-top-right-radius: 20px; border-top-left-radius: 20px;'>
         <h2>Food Hub | Centro Cuyapo</h2>
        </div>
        <div style='padding: 30px 12px; background-color: aliceblue;'>
          <h4>Email Verification Code</h4>
          <p style='text-align: center; font-size: 18px; color:white;'>Hi $name , <br/>
            Thank you for choosing us. Here's your Verification Code:  
        </p>
        <h4 style='text-align: center; font-size: 18px;'>
           $verification_code 
        </h4>
        <div style='text-align:center;'>
          <a href='" . $link . "' style='font-size: 14px; background-color: black; border-radius: 10px; text-decoration: none; color: white; text-transform: capitalize; padding: 5px 10px;'>click here to redirect</a>
        </div>
    </div>
        <div style='text-align: center; background-color: orange; padding: 10px 5px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;'>
          <h5 style='color:white; margin:0;'>@foodhubcuyapo</h5>
        </div>
      </div>
</div>";

      $this->mail->send();
      return true;
    } catch (Exception $e) {
      return false;
    }
  }
}

// Usage:
$emailSender = new EmailSender();
$linkS  = $rootUrl . "/login/verification.php";

if (isset($_POST['submit'])) {
  // Gather form data
  $name = $_POST['name'];
  $birthdate = $_POST['birthdate'];
  $gender = $_POST['gender'];
  $userType = $_POST['user_type'];
  $email = $_POST['email'];
  $password1 = $_POST['password1'];
  $password2 = $_POST['password2'];
  $verification_code = mt_rand(100000, 999999);
  if ($password1 !== $password2) {
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Your Password Not Match!",
                    text: "Please Check your inputs.",
                    icon: "error"
                });
            });
        </script>';
  } else {
    // Check if the email already exists
    $check_email_query = "SELECT COUNT(*) as count FROM users WHERE email = '$email'";
    $result = $conn->query($check_email_query);

    $row = $result->fetch_assoc();
    $email_count = $row['count'];

    if ($email_count > 0) {
      // Email already exists, display JavaScript alert
      echo '<script src="../sweetalert2.min.js"></script>';
      echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "Email Already Exist!",
                        text: "Please Check your Email",
                        icon: "info"
                    });
                });
            </script>';
            
    } else {
      // Insert into credentials table
      $sql_credentials = "INSERT INTO users (email, password, account_type,email_ver_num) VALUES ('$email', '$password1', '$userType','$verification_code')";

      if ($conn->query($sql_credentials) === TRUE) {
        $user_id = $conn->insert_id;

        // Send verification email

        if ($emailSender->sendVerificationEmail($email, $name, $linkS, $verification_code)) {
          // Email sent successfully, store verification code in session or database if needed
         echo '<script src="../sweetalert2.min.js"></script>';
          echo '
          <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                title: "Registration Successful",
                                text: "Nice!",
                                icon: "success"
                            });
                            setTimeout(function() {
                                window.location.href = "verification.php";
                            }, 2000);
                        });
                    </script>';

          // Insert user details
          if ($userType == 'customer') {
            $sql = "INSERT INTO customer (user_id, name, birthdate, gender, date_created) VALUES ('$user_id','$name','$birthdate','$gender',NOW())";
            $result = $conn->query($sql);

            if( $result){

            }
          } else {
            echo "Error Connecting to" . $conn;
          }
        } else {
          echo "Failed to send verification email.";
        }
      }
    }
    $conn->close();
  }
}
?>





<!doctype html>
<html lang="en" dir="ltr">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Food Hub</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.min.css"> 
  <link rel="stylesheet" href="../sweetalert2.min.css"> 

  <!-- <link rel="stylesheet" href="sweetalert2.min.css"> -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/plugin.min.css">
  <link rel="stylesheet" href="../customize.css">
  <!-- <link rel="icon" type="image/png" sizes="16x16" href="img/favicon.png"> -->
  <link rel="stylesheet" href="../../../unicons.iconscout.com/release/v3.0.0/css/line.css">
  <link rel="stylesheet" href="./css/datepicker.css">


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
    <div class="admin">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-xxl-3 col-xl-4 col-md-6 col-sm-8">
            <div class="edit-profile">

              <div class="container">
                <div class="heading">Sign Up</div>
                <form class="form" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                  <input placeholder="Full Name" id="name" name="name" type="text" class="input" required />
                  <input id="date" name="birthdate" type="input" max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>" class="input datepicker" placeholder="Birthdate" required />
                  <select class="px-sm-2 input" name="gender">
                    <option Selected disabled required>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="not">Prefer not to say</option> 
                  </select>
                  <input type="hidden" name="user_type" value="customer">
                  <input placeholder="E-mail" id="email" name="email" type="email" class="input" required />
                  <input placeholder="Password" id="password" name="password1" type="password" class="input" required="" />
                  <input placeholder="Confirm Password" id="password2" name="password2" type="password" class="input" required />

                  <div id="password-strength-message" class="mb-15 agreement"></div>
                  <span id="password-match-message" class="mb-15 agreement"></span>
                  <button type="submit" id="signUp" name="submit" class="login-button">Register</button>


                  <div class="admin-condition">
                    <div class="checkbox-theme-default custom-checkbox agreement">
                      <input class="checkbox" type="checkbox" id="admin-1" required>
                      <label for="admin-1">
                        <span class="checkbox-text agreement">I agree with
                          <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#terms" class="color-primary">
                            Terms of Service and Privacy Policy
                          </a>
                        </span>
                      </label>
                    </div>
                  </div>
                  <div class="modal fade" id="terms" tabindex="-1" role="dialog" aria-labelledby="terms" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="terms"> Terms of Service and Privacy Policy</h5>
                          <button type="button" class="close btn btn-warning" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                         <?php 
                         $term = $conn->query("SELECT * FROM policy_term ORDER BY date_updated DESC");
                         if( $term ){
                           $res_term = $term->fetch_assoc();
                         }

                         
                         if($res_term){
                          echo "<pre>" . htmlspecialchars($res_term['terms_policies']) . "</pre>";
                         }
                         ?>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="admin-topbar text-center mt-md-4">
                    <p class="mb-0">

                      <a href="index.php" class="text-dark text-decoration-none">
                        Sign In
                      </a>
                    </p>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>
  <div id="overlayer">
    <div class="loader-overlay">
      <div class="dm-spin-dots spin-lg">
        <span class="spin-dot badge-dot dot-primary"></span>
        <span class="spin-dot badge-dot dot-primary"></span>
        <span class="spin-dot badge-dot dot-primary"></span>
        <span class="spin-dot badge-dot dot-primary"></span>
      </div>
    </div>
  </div>
  <div class="enable-dark-mode dark-trigger">
    <ul>
      <li>
        <!-- <a href="#"> -->
        <i class="uil uil-moon"></i>
        </a>
      </li>
    </ul>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.all.min.js"></script>
  <script src="../js/plugins.min.js"></script>
  <script src="./js/bootstrap-datepicker.js"></script>
  <script src="../sweetalert2.min.js"></script>

  <script>
   



    // const userTypeSelect = document.getElementById('userTypeSelect');
    // const idPictureUpload = document.getElementById('idPictureUpload');

    // userTypeSelect.addEventListener('change', function() {
    //   if (userTypeSelect.value === 'owner') {
    //     idPictureUpload.style.display = 'block';
    //   } else {
    //     idPictureUpload.style.display = 'none';
    //   }
    // });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('password').addEventListener('input', function() {
        var password = this.value;
        validatePasswordStrength(password);
        checkPasswordMatch(password);
      });

      document.getElementById('password2').addEventListener('input', function() {
        var password = document.getElementById('password').value;
        var confirmPassword = this.value;
        if (confirmPassword.trim() !== '') {
          checkPasswordMatch(password, confirmPassword);
        } else {
          document.getElementById('password-match-message').innerText = '';
        }
      });
    });

    function validatePasswordStrength(password) {
      var strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;
      var strengthMessage = document.getElementById('password-strength-message');
      if (strongRegex.test(password)) {
        strengthMessage.innerText = 'Password strength: Strong';
        strengthMessage.style.color = 'green';
      } else {
        strengthMessage.innerText = 'Password strength: Weak';
        strengthMessage.style.color = 'red';
      }
    }

    function checkPasswordMatch(password, confirmPassword) {
      var message = document.getElementById('password-match-message');
      var strengthMessage1 = document.getElementById('password-strength-message');
      var signupButton = document.querySelector('#signUp');
      if (password === confirmPassword && password !== '' && confirmPassword !== '') {
        var strengthMessage = "Strong Password.";
        message.innerText = 'Passwords match and ' + strengthMessage.toLowerCase();
        message.style.color = 'green';
        strengthMessage1.style.display = 'none';
        signupButton.disabled = false;
      } else {
        message.innerText = 'Passwords do not match';
        message.style.color = 'red';
        signupButton.disabled = true;
      }
    }
  </script>
  <!-- B320693378489E984BC26F3731436011F7D9 -->
  <script>
    function validateForm() {
      var password = document.getElementById("password").value;
      const password2 = document.getElementById("password2").value;
      if (password.length < 8) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Password must be at least 8 characters long',
        });
        return false;
      }

      return true;
    }
  </script>

  <script>
                  $('.datepicker').datepicker()
        </script>

</body>


</html>