<?php
include('sidebar.php');

if (isset($_POST['update'])) {

  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $contactNumber = $_POST['contactNumber'];

  $credentials_query = "UPDATE users SET email = '$email', password = '$password' WHERE user_id = $user_id";
  if ($conn->query($credentials_query) === true) {
    echo "Credentials updated successfully. ";
  } else {
    echo "Error updating credentials: " . $conn->error;
  }
}


if (isset($_POST['update_id'])) {
    $exist_id = "SELECT * FROM employee_photo WHERE employee_id = $user_id";
    $result_id_exist = $conn->query($exist_id);

    if ($result_id_exist->num_rows > 0) {
        if (isset($_FILES['profile_id']) && $_FILES['profile_id']['error'] === UPLOAD_ERR_OK) {
            $fileName = basename($_FILES["profile_id"]["name"]);
            $fileTmpName = $_FILES["profile_id"]["tmp_name"];
            $uploadDir = "./uploads/";
            $filePath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($fileTmpName, $filePath)) {
                $update_upload = "UPDATE employee_photo SET photo_path = '$filePath' WHERE employee_id = $user_id";
                if ($conn->query($update_upload) === TRUE) {
                    echo '<script>
                        window.location.href = "profile.php";
                    </script>';
                    exit();
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                echo "Error uploading the file.";
            }
        }
    } else {
        if (isset($_FILES['profile_id']) && $_FILES['profile_id']['error'] === UPLOAD_ERR_OK) {
            $fileName = basename($_FILES["profile_id"]["name"]);
            $fileTmpName = $_FILES["profile_id"]["tmp_name"];
            $uploadDir = "./uploads/";
            $filePath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($fileTmpName, $filePath)) {
                $sql = "INSERT INTO employee_photo (employee_id, photo_path) VALUES ('$employee_id', '$filePath')";
                if ($conn->query($sql) === TRUE) {
                    echo '<script>alert("Image uploaded and saved successfully.");</script>';
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Error uploading the file.";
            }
        }
    }
}

?>
<div class="contents vh-100" style="background:rgb(252, 250, 241)">
  <div class="container-fluid">
    <div class="social-dash-wrap">
      <div class="row">
        <div class="col-lg-12">
          <div class="breadcrumb-main">
            <h4 class="text-capitalize breadcrumb-title">Profile</h4>
            <div class="breadcrumb-action justify-content-center flex-wrap">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
      
      <div class="row d-flex flex-lg-row flex-column">
    <!-- Profile Form Section -->
    <div class="col-lg-8 col-md-12 mb-4">
      <div class="card bg-warning-light">
        <div class="card-body">
          <form method="post" action="">
            <div class="form-group row mb-3">
              <label for="inputNameIcon" class="col-sm-3 col-form-label text-dark">Name</label>
              <div class="col-sm-9">
                <div class="input-group">
                  <span class="input-group-text"><i class="la-user lar"></i></span>
                  <input type="text" class="form-control" id="inputNameIcon" name="name" value="<?php echo $name; ?>">
                </div>
              </div>
            </div>
            <div class="form-group row mb-3">
              <label for="inputEmailIcon" class="col-sm-3 col-form-label text-dark">Email Address</label>
              <div class="col-sm-9">
                <div class="input-group">
                  <span class="input-group-text"><i class="lar la-envelope"></i></span>
                  <input type="email" class="form-control" id="inputEmailIcon" name="email" value="<?php echo $email; ?>">
                </div>
              </div>
            </div>
            <div class="form-group row mb-3">
              <label for="inputPasswordIcon" class="col-sm-3 col-form-label text-dark">Password</label>
              <div class="col-sm-9">
                <div class="input-group">
                  <span class="input-group-text"><i class="las la-lock"></i></span>
                  <input type="password" class="form-control" id="inputPasswordIcon" name="password" value="<?php echo $password; ?>">
                </div>
              </div>
            </div>
            <div class="form-group row mb-4">
              <label for="inputConfirmPasswordIcon" class="col-sm-3 col-form-label text-dark">Confirm Password</label>
              <div class="col-sm-9">
                <div class="input-group">
                  <span class="input-group-text"><i class="las la-lock"></i></span>
                  <input type="password" class="form-control" id="inputConfirmPasswordIcon" name="confirm_password">
                </div>
                <div class="layout-button mt-25">
                        <button type="submit" name="update" class="btn btn-default btn-squared px-30 text-white" style="background:rgb(39, 35, 67)">Update</button>
                      </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Employee ID Card Section -->
    <div class="col-lg-4 col-md-12 mb-4">
      <form method="POST" enctype="multipart/form-data">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Employee ID</span>
            <button class="btn btn-squate btn-gray update-btn" type="submit" name="update_id">Update ID</button>

          </div>
          <div class="card-body text-center">
            <img id="preview" src="<?php echo $image_path?>" alt="id" class="img-fluid mb-3" style="max-height: 200px;">
            <div>
            <label for="upload" class="upload-label">Select Photo</label>
            <input type="file" id="upload" class="upload-btn" name="profile_id" accept="image/*" onchange="previewImage(event)">
            </div>
          </div>
          <div class="card-footer text-left">
            <h6>Name: <span class="fw-normal"><?php echo $name ?></span></h6>
            <h6>Position: <span class="fw-normal"><?php echo $employee_type ?></span></h6>
          </div>
        </div>
      </form>
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
<script src="js/plugins.min.js"></script>
<script src="js/script.min.js"></script>
<script>
  $((function() {
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
      construct: function(t) {
        this._super(t);
        this.jobTitles = ["Active", "Pending", "Rejected"];
        this.jobTitleDefault = "All";
        this.$jobTitle = null;
      },
      $create: function() {
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
        $.each(t.jobTitles, (function(e, s) {
          t.$jobTitle.append($("<option/>").text(s));
        }));
      },
      _onJobTitleDropdownChanged: function(t) {
        var e = t.data.self,
          s = $(this).val();
        s !== e.jobTitleDefault ? e.addFilter("status", s, ["status"]) : e.removeFilter("status");
        e.filter();
      },
      draw: function() {
        this._super();
        var e = this.find("status");
        e instanceof FooTable.Filter ? this.$jobTitle.val(e.query.val()) : this.$jobTitle.val(this.jobTitleDefault);
      }
    });
</script>

<script>
 function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview');
        const uploadLabel = document.getElementById('uploadLabel');
        const updateBtn = document.querySelector('.update-btn');

        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                preview.src = reader.result;
            };
            reader.readAsDataURL(file);

            updateBtn.style.display = 'block';
        } else {
            updateBtn.style.display = 'none';
        }
    }
</script>

</body>


</html>