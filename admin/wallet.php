<?php
include('sidebar.php');

// $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

// $host = $_SERVER['HTTP_HOST']; 

// $script = $_SERVER['PHP_SELF']; 

// $current_url = $protocol . '://' . $host . $script . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');


$sql_credentials = "SELECT * FROM users WHERE account_type ='admin'";
$result_credentials = mysqli_query($conn, $sql_credentials);

if ($row_credentials = mysqli_fetch_assoc($result_credentials)) {
  $user_type = $row_credentials['account_type'];
  $email = $row_credentials['email'];
  $password = $row_credentials['password'];
}


// if (isset($_POST['change'])) {
//   $email = $_POST['email'];
//   $password = $_POST['password'];
//   $name = $_POST['name'];

//   $credentials_query = "UPDATE users SET email = '$email', password = '$password' WHERE account_type ='admin'";
//   if ($conn->query($credentials_query) === true) {

//     $update = "UPDATE admin SET admin_name = '$name' WHERE user_id = '$admin_id'";
//     $result_update = $conn->query($update);
//     if ($result_update) {
//       echo '<script>
//                   document.addEventListener("DOMContentLoaded", function() {
//                     Swal.fire({
//                         title: "Changes has been Saved!",
//                         text: "Credentials are up to date.",
//                         icon: "success"
//                     }).then(function() {
//                       window.location.href = "index.php"; 
//                       exit();
//                     });
//                 });
//                   </script>';
//     }
//     echo '<script>
//                   document.addEventListener("DOMContentLoaded", function() {
//                     Swal.fire({
//                         title: "Changes has been Saved!",
//                         text: "Credentials are up to date.",
//                         icon: "success"
//                     }).then(function() {
//                       window.location.href = "index.php"; 
//                       exit();
//                     });
//                 });
//                   </script>';
//   } else {
//     echo "Error updating credentials: " . $conn->error;
//   }
// }


if (isset($_POST['setup'])) {

  $name = $_POST['account_name'];
  $email = $_POST['email'];


  if (isset($_FILES['wallet_qr']) && $_FILES['wallet_qr']['error'] == 0) {
    $fileTmpPath = $_FILES['wallet_qr']['tmp_name'];
    $fileName = $_FILES['wallet_qr']['name'];
    $fileSize = $_FILES['wallet_qr']['size'];
    $fileType = $_FILES['wallet_qr']['type'];

    $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');

    if (in_array($fileType, $allowedTypes)) {
      $uploadDir = '../uploads/';
      $destPath = $uploadDir . $fileName;

      if (move_uploaded_file($fileTmpPath, $destPath)) {
        echo "File uploaded successfully: " . $fileName;
      } else {
        echo "Error moving uploaded file.";
      }
    } else {
      echo "Invalid file type. Please upload an image (JPG, PNG, GIF).";
    }


    // insert to database


    $wallet_data = "INSERT INTO wallet (user_id, name, wallet_qr, email) VALUES ('$admin_id', '$name', '$fileName', '$email')";

    $save_wallet = $conn->query($wallet_data);

    if ($save_wallet) {
      echo '<script>
      document.addEventListener("DOMContentLoaded", function() {
          Swal.fire({
              title: "Your waller has been Set Up.",
              text: "success",
              icon: "success"
          });
      });
  </script>';
    }
  } else {
    echo "No file uploaded or there was an error uploading the file.";
  }
}


$scheck_active_wallet = "SELECT * FROM wallet WHERE user_id = $admin_id";

$result = $conn->query($scheck_active_wallet);

$active = false;

if ($result->num_rows > 0) {

  $active = true;

  while ($rw = $result->fetch_assoc()) {

    $file_name = $rw['wallet_qr'];
  }
}


if (isset($_POST['update'])) {
  $name = $_POST['account_name'];
  $email = $_POST['email'];
  $user_id = $admin_id;  // Assuming $admin_id is defined somewhere else in your code

  // Check if a file is uploaded
  if (isset($_FILES['wallet_qr']) && $_FILES['wallet_qr']['error'] == 0) {
    $fileTmpPath = $_FILES['wallet_qr']['tmp_name'];
    $fileName = $_FILES['wallet_qr']['name'];
    $fileSize = $_FILES['wallet_qr']['size'];
    $fileType = $_FILES['wallet_qr']['type'];

    // Allowed file types
    $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');

    if (in_array($fileType, $allowedTypes)) {
      $uploadDir = '../uploads/';
      $destPath = $uploadDir . $fileName;

      // Move the uploaded file to the desired location
      if (move_uploaded_file($fileTmpPath, $destPath)) {
        // File upload successful, now update the database
        $query = "UPDATE wallet SET name = '$name', wallet_qr = '$fileName', email = '$email' WHERE user_id = '$user_id'";

        $update_wallet = $conn->query($query);

        // If the update is successful, display success message
        if ($update_wallet) {
          echo '<script>
                  document.addEventListener("DOMContentLoaded", function() {
                      Swal.fire({
                          title: "Your wallet has been updated.",
                          text: "success",
                          icon: "success"
                      });
                  });
                  </script>';
        } else {
          echo "Error updating the wallet information in the database.";
        }
      } else {
        echo "Error moving uploaded file.";
      }
    } else {
      echo "Invalid file type. Please upload an image (JPG, PNG, GIF).";
    }
  } else {
    // If no file is uploaded, just update the name and email
    $query = "UPDATE wallet SET name = '$name', email = '$email' WHERE user_id = '$user_id'";

    $update_wallet = $conn->query($query);

    if ($update_wallet) {
      echo '<script>
          document.addEventListener("DOMContentLoaded", function() {
              Swal.fire({
                  title: "Your wallet has been updated.",
                  text: "success",
                  icon: "success"
              });
          });
          </script>';
    } else {
      echo "Error updating the wallet information in the database.";
    }
  }
}

?>
<div class="contents">
  <div class="container-fluid flex-row-reverse">
    <div class="social-dash-wrap">
      <div class="row">
        <div class="col-lg-12">
          <div class="breadcrumb-main px-2">
            <h4 class="text-capitalize breadcrumb-title">Wallet</h4>
            <div class="breadcrumb-action justify-content-center flex-wrap">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Dashboard
                    </a></li>
                  <li class="breadcrumb-item active" aria-current="page">Wallet</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex">
        <div class="col-md-6 mx-4">
          <div class="card"
            style="box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px;">
            <div class="card-body">
              <div class="horizontal-form">
                <form method="POST" enctype="multipart/form-data">

                  <div class="form-group row mb-25">
                    <div class="col-sm-3 d-flex aling-items-center">
                      <label for="inputEmailIcon"
                        class="col-form-label color-dark fs-14 fw-500 align-center mb-10">Name</label>
                    </div>
                    <div class="col-sm-9">
                      <div class="with-icon">
                        <span class="las la-user-cog"></span>
                        <input type="text" class="form-control ih-medium ip-gray radius-xs b-light" name="account_name"
                          required>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row mb-25">
                    <div class="col-sm-3 d-flex aling-items-center">
                      <label for="inputEmailIcon"
                        class="col-form-label color-dark fs-14 fw-500 align-center mb-10">Email Address</label>
                    </div>
                    <div class="col-sm-9">
                      <div class="with-icon">
                        <span class="lar la-envelope color-gray"></span>
                        <input type="email" class="form-control ih-medium ip-gray radius-xs b-light" id="inputEmailIcon"
                          name="email" value="<?php echo $email; ?>" required>
                      </div>
                    </div>
                  </div>
                  <?php if (!$active): ?>
                    <div class="form-group row mb-4">
                      <div class="col-sm-3 d-flex align-items-center">
                        <label for="wallet_qr" class="col-form-label color-dark fs-13 fw-500 mb-0">Upload Wallet
                          QR</label>
                      </div>
                      <div class="col-sm-9">
                        <div class="input-group">
                          <div class="d-flex align-items-center">
                            <!-- Custom file input for better styling in Bootstrap -->
                            <input type="file" id="wallet_qr" name="wallet_qr"
                              class="form-control ip-gray radius-xs w-full" accept="image/*" required
                              onchange="previewImage()">
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="layout-button mt-25">
                      <button type="submit" name="setup" class="btn btn-primary btn-default btn-squared px-30">Set
                        Up</button>
                    </div>
                  <?php else: ?>
                    <div class="form-group row mb-4">
                      <div class="col-sm-3 d-flex align-items-center">
                        <label for="wallet_qr" class="col-form-label color-dark fs-13 fw-500 mb-0">Upload Wallet
                          QR</label>
                      </div>
                      <div class="col-sm-9">
                        <div class="input-group">
                          <div class="d-flex align-items-center">
                            <!-- Custom file input for better styling in Bootstrap -->
                            <input type="file" id="wallet_qr" name="wallet_qr"
                              class="form-control ip-gray radius-xs w-full" accept="image/*" required
                              onchange="previewImage()">
                          </div>
                        </div>
                      </div>
                    </div>


                    <div class="layout-button mt-25">
                      <button type="submit" name="update" class="btn btn-primary btn-default btn-squared px-20">Update
                        Wallte QR</button>
                    </div>


                  <?php endif; ?>

                </form>

              </div>
            </div>
          </div>
        </div>


        <!-- settins -->


        <!-----promo -->




        <?php if (!$active): ?>
          <div class="row">
            <h4 id="title">UPDATE WALLET QR</h4>
            <div id="imagePreviewContainer" style="margin-top: 10px;">
              <img id="imagePreview" src="" alt="Image Preview"
                style="max-width: 100%; max-height: 300px; display: none;">
            </div>
          </div>

        <?php else: ?>
          <div class="row">
            <div class="d-flex  ">
              <span class="badge bg-success rounded-pill d-inline-block mx-3"></span>
              <h4 id="title">ACTIVE WALLET QR</h4>
            </div>
            <div id="imagePreviewContainer" style="margin-top: 10px;">
              <!-- Display the existing QR image -->
              <img src="../uploads/<?php echo $file_name; ?>" id="imagePreview" alt="Image Preview"
                style="max-width: 100%; max-height: 300px;">
            </div>

          </div>
        <?php endif; ?>


      </div>
    </div>


    <!--- promo modal -->
    <!-- update -->
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
<div class="overlay-dark-sidebar"></div>
<div class="customizer-overlay"></div>
<div class="customizer-wrapper">
  <div class="customizer">
    <div class="customizer__head">
      <h4 class="customizer__title">Customizer</h4>
      <span class="customizer__sub-title">Customize your overview page layout</span>
      <a href="#" class="customizer-close">
        <img class="svg" src="img/svg/x2.svg" alt>
      </a>
    </div>
    <div class="customizer__body">
      <div class="customizer__single">
        <h4>Layout Type</h4>
        <ul class="customizer-list d-flex layout">
          <li class="customizer-list__item">
            <a href="http://demo.dashboardmarket.com/hexadash-html/ltr" class="active">
              <img src="img/ltr.png" alt>
              <i class="fa fa-check-circle"></i>
            </a>
          </li>
          <li class="customizer-list__item">
            <a href="http://demo.dashboardmarket.com/hexadash-html/rtl">
              <img src="img/rtl.png" alt>
              <i class="fa fa-check-circle"></i>
            </a>
          </li>
        </ul>
      </div>
      <div class="customizer__single">
        <h4>Sidebar Type</h4>
        <ul class="customizer-list d-flex l_sidebar">
          <li class="customizer-list__item">
            <a href="#" data-layout="light" class="dark-mode-toggle active">
              <img src="img/light.png" alt>
              <i class="fa fa-check-circle"></i>
            </a>
          </li>
          <li class="customizer-list__item">
            <a href="#" data-layout="dark" class="dark-mode-toggle">
              <img src="img/dark.png" alt>
              <i class="fa fa-check-circle"></i>
            </a>
          </li>
        </ul>
      </div>
      <div class="customizer__single">
        <h4>Navbar Type</h4>
        <ul class="customizer-list d-flex l_navbar">
          <li class="customizer-list__item">
            <a href="#" data-layout="side" class="active">
              <img src="img/side.png" alt>
              <i class="fa fa-check-circle"></i>
            </a>
          </li>
          <li class="customizer-list__item top">
            <a href="#" data-layout="top">
              <img src="img/top.png" alt>
              <i class="fa fa-check-circle"></i>
            </a>
          </li>
          <li class="colors"></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.all.min.js"></script>
<script src="js/plugins.min.js"></script>
<script src="js/script.min.js"></script>

<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script> -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
  function previewImage() {
    const fileInput = document.getElementById('wallet_qr');
    const preview = document.getElementById('imagePreview');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const activeText = document.getElementById('title');

    const file = fileInput.files[0];
    if (file && file.type.startsWith('image')) {
      const reader = new FileReader();

      reader.onload = function (e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
        imagePreviewContainer.style.display = 'block';
        activeText.innerHTML = 'UPDATE WALLET QR';
      };

      // Read the file as a data URL
      reader.readAsDataURL(file);
    } else {
      // If not an image, clear the preview and hide it
      preview.src = '';
      preview.style.display = 'none';
      imagePreviewContainer.style.display = 'none';
      activeText.innerHTML = 'UPDATE WALLET QR';
    }
  }
</script>
<script>
  $((function () {
    $(".adv-table1").footable({
      filtering: {
        enabled: !0
      },
      paging: {
        enabled: !0,
        current: 1
      },
      strings: {
        enabled: !1
      },
      filtering: {
        enabled: !0
      },
      components: {
        filtering: FooTable.MyFiltering
      }
    })
  })),
    FooTable.MyFiltering = FooTable.Filtering.extend({
      construct: function (t) {
        this._super(t);
        this.jobTitles = ["Active", "Pending", "Rejected"];
        this.jobTitleDefault = "All";
        this.$jobTitle = null;
      },
      $create: function () {
        this._super();
        var t = this,
          s = $("<div/>", {
            class: "form-group dm-select d-flex align-items-center adv-table-searchs__status my-xl-25 my-15 mb-0 me-sm-30 me-0"
          }).append($("<label/>", {
            class: "d-flex align-items-center mb-sm-0 mb-2",
            text: "Status"
          })).prependTo(t.$form);
        t.$jobTitle = $("<select/>", {
          class: "form-control ms-sm-10 ms-0"
        }).on("change", {
          self: t
        }, t._onJobTitleDropdownChanged).append($("<option/>", {
          text: t.jobTitleDefault
        })).appendTo(s);
        $.each(t.jobTitles, (function (e, s) {
          t.$jobTitle.append($("<option/>").text(s));
        }));
      },
      _onJobTitleDropdownChanged: function (t) {
        var e = t.data.self,
          s = $(this).val();
        s !== e.jobTitleDefault ? e.addFilter("status", s, ["status"]) : e.removeFilter("status");
        e.filter();
      },
      draw: function () {
        this._super();
        var e = this.find("status");
        e instanceof FooTable.Filter ? this.$jobTitle.val(e.query.val()) : this.$jobTitle.val(this.jobTitleDefault);
      }
    });
</script>

<script>
  function select_item() {
    var button_with = document.getElementById("promos_bundles");
    var list_selection = document.getElementById("selections").value;
    $.ajax({
      url: "updata",
      method: "POST",
      data: {
        item_selected: list_selection
      },
      success: function (data) {
        $("#product_details_selected").html(data);
      }
    })
    button_with.classList.add('d-none');

  }
</script>
<script>
  function bundle() {
    var section_promo = document.getElementById("promos");
    var toggle_close = document.getElementById("close_promo");
  }
</script>
<script>
  function showpasswordAdmin() {
    let passwordInput = document.getElementById('password');
    let buttonView = document.getElementById('showpass');

    if (passwordInput.type == 'password') {
      passwordInput.type = 'text';
      toggleIcon.classList.remove('fa-eye');
    } else {
      passwordInput.type = 'password';
      toggleIcon.classList.remove('fa-eye-slash');
    }
  }
</script>
</body>


</html>