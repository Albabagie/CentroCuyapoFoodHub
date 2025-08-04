<?php
include ('sidebar.php');


$sql_credentials = "SELECT * FROM users WHERE account_type ='admin'";
$result_credentials = mysqli_query($conn, $sql_credentials);

if ($row_credentials = mysqli_fetch_assoc($result_credentials)) {
  $user_type = $row_credentials['account_type'];
  $email = $row_credentials['email'];
  $password = $row_credentials['password'];
}


if (isset($_POST['change'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $name = $_POST['name'];

    $credentials_query = "UPDATE users SET email = '$email', password = '$password' WHERE account_type ='admin'";
  if ($conn->query($credentials_query) === true) {

    $update = "UPDATE admin SET admin_name = '$name' WHERE user_id = '$admin_id'";
    $result_update = $conn->query($update);
    if($result_update){
    echo '<script>
                  document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "Changes has been Saved!",
                        text: "Credentials are up to date.",
                        icon: "success"
                    }).then(function() {
                      window.location.href = "index.php"; 
                      exit();
                    });
                });
                  </script>';
      }
      echo '<script>
                  document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "Changes has been Saved!",
                        text: "Credentials are up to date.",
                        icon: "success"
                    }).then(function() {
                      window.location.href = "index.php"; 
                      exit();
                    });
                });
                  </script>';
  } else {
    echo "Error updating credentials: " . $conn->error;
  }
}


?>
<div class="contents">
  <div class="container-fluid flex-row-reverse">
    <div class="social-dash-wrap">
      <div class="row">
        <div class="col-lg-12">
          <div class="breadcrumb-main px-2">
            <h4 class="text-capitalize breadcrumb-title">Profile</h4>
            <div class="breadcrumb-action justify-content-center flex-wrap">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Dashboard
                    </a></li>
                  <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px;">
            <div class="card-body">
              <div class="horizontal-form">
                <form method="POST">
                  
                  <div class="form-group row mb-25">
                    <div class="col-sm-3 d-flex aling-items-center">
                      <label for="inputEmailIcon" class="col-form-label color-dark fs-14 fw-500 align-center mb-10">Name</label>
                    </div>
                    <div class="col-sm-9">
                      <div class="with-icon">
                        <span class="las la-user-cog"></span>
                        <input type="text" class="form-control ih-medium ip-gray radius-xs b-light" id="inputEmailIcon" name="name" value="<?php echo $admin_name; ?>" required>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group row mb-25">
                    <div class="col-sm-3 d-flex aling-items-center">
                      <label for="inputEmailIcon" class="col-form-label color-dark fs-14 fw-500 align-center mb-10">Email Address</label>
                    </div>
                    <div class="col-sm-9">
                      <div class="with-icon">
                        <span class="lar la-envelope color-gray"></span>
                        <input type="email" class="form-control ih-medium ip-gray radius-xs b-light" id="inputEmailIcon" name="email" value="<?php echo $email; ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row mb-25">
                    <div class="col-sm-3 d-flex aling-items-center">
                      <label for="inputPasswordIcon" class="col-form-label color-dark fs-14 fw-500 align-center mb-10">Password</label>
                    </div>
                    <div class="col-sm-9">
                      <div class="input-group">
                      <span class="input-group-text bg-none border-left-0"><span class="las la-lock "></span></span>
                      <input type="password" id="password" name="password" class="form-control ih-medium ip-gray radius-xs b-light" value="<?php echo $password ?>" required>
                      <span class="input-group-text bg-none" id="showpass" type="button"  onclick="showpasswordAdmin()"><span class="las la-user-secret"></span></span>
                    </div>
                    </div>
                  </div>

                  <div class="form-group row mb-0">
                    <div class="col-sm-3">
                      <label for="inputConfirmPasswordIcon" class="col-form-label color-dark fs-14 fw-500 align-center mb-10">Confirm Password</label>
                    </div>
                    <div class="col-sm-9">
                      <div class="with-icon">
                        <span class="las la-lock color-gray"></span>
                        <input type="password" class="form-control ih-medium ip-gray radius-xs b-light" id="inputConfirmPasswordIcon" name="confirm_password" required>
                      </div>
                      <div class="layout-button mt-25">
                        <button type="submit" name="change" class="btn btn-warning btn-default btn-squared px-30">change</button>
                      </div>
                    </div>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>


        <!-- settins -->
        <div class="col-md-6 mb-2 ">
          <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px;">
            <div class="card-body">
              <div class="card-header mb-2">
            <h4>Terms and Conditions</h4>
              </div>
              <div class="horizontal-form my-md-2">
                 
                  <div class="form-group row mb-2">
                   
                    <div class="col-md-12">
                      <?php
                      $terms_policies = "SELECT * FROM policy_term ORDER BY date_updated DESC";
                      $res_term = $conn->query($terms_policies);
                      $row = $res_term->fetch_assoc();
                      ?>
                      <div class="">
                        
                      <textarea class="form-control" rows="10" readonly>
                        <?php 
                        if(!empty($row['terms_policies'])){
                          echo $row['terms_policies'];
                        } else{
                          echo 'No Polices and Terms Set';
                        }
                      ?>
                        </textarea>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row mb-0">
                    <div class="col-sm-9">
                      <div class="layout-button mt-25 text-end">
                        <button type="button"  class="btn btn-primary  btn-squared px-30" data-toggle="modal" data-target="#view_terms">view</button>
                        <button type="button"  class="btn btn-success  btn-squared px-30" data-toggle="modal" data-target="#update_terms">update</button>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>

      <!-----promo -->
      <div class="col-lg-12 my-15">
          <div class="card">
          <div class="card-header"> 
          <h3>Promo<h3>
          <button class="btn btn-primary" data-toggle="modal" data-target="#promos">Add Active Promo</button>
          </div>
          <div class="card-body d-flex flex-wrap px-1">
          <?php 
        $p_promos = "SELECT * FROM promo p JOIN inventory i ON i.product_id = p.item_id LEFT JOIN product_img pi ON pi.product_id = i.product_id WHERE promo_status = 'active'";
        $res_promo = $conn->query($p_promos);
        
        if($res_promo && $res_promo->num_rows > 0){
          while($row_pro = $res_promo->fetch_assoc()){
            echo '<div class="card col-lg-4 mb-15">';
            echo '<a class=" mb-15" href="viewitem?product_id='.$row_pro['product_id'].'">
           
            <div class="px-1">
                  <div class="ratio-4x3 position-relative overflow-hidden" style="height:100px;">
                    <img src="../uploads/'.$row_pro['img_name'].'" class="card-img-top" alt="product_promo">
                    </div>
                    <div class="card-body p-2">
                      <p class="card-text text-dark">'.$row_pro['product_name'].' <span class="text-dark text-decoration-line-through">Php.'.$row_pro['item_promo_price'].'</span><span class="badge-pill bg-warning rounded text-white p-1 mx-2">Php. '.number_format($row_pro['item_current_price'],2).'<span class="badge rounded badge-success position-absolute top-0 start-0" style="font-size:14px;height:40px;">New</span></span></p>
                    </div>
                   <div class="col-md-10">
                   <span class="text-light mx-2">Promo Expire at: '.date('Y-m-d H:i', strtotime($row_pro['active_promo'])).'</span>
                   </div>
                  </div>
                  </a>
                   <div class="layout-button mb-15 row flex-column">
                   <form method="POST" action="updata">
                    <input type="hidden" value="'. $row_pro['promo_id'].'" name="promo_id">
                     <button type="submit" class="btn btn-sm btn-danger-hover" name="delete_promo">Delete</button>
                     </form>
                     </div>'
                  ;
                  echo '</div>';
          }
        } else {
          echo 'No Active Promo';
        }
          ?>
          </div>
        </div>
        </div>




        <div class="modal fade" id="promos" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="promosLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="promosLabel">Promo | Cuyapo Food Hub</h5>
                      <button type="button" id="close_promo" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
                  </div>
                  <form method="POST" action="updata">
                  <div class="modal-body">
                    <div class="form-group">
                      <label for="promo_name">Promo Name</label>
                      <input type="text" class="form-control ih-medium ip-gray radius-xs b-light" name="promo_name" required>
                    </div>
                    <!-- items -->

                    <div class="form-group">
                      <label for="product_promo">List of Products</label>
                      <select name="item_id" class="form-control ih-medium ip-gray radius-xs b-light" id="selections" onchange="select_item()">
                        <option class="form-control" selected disabled required>Select Product</option>
                      <?php 
                                       $item_list = "SELECT i.* FROM inventory i
                                       WHERE i.state = 0
                                       AND NOT EXISTS (
                                           SELECT 1 FROM promo p
                                           WHERE p.item_id = i.product_id
                                           AND p.promo_status = 'active'
                                       )
                                   ";
                                        $result_list = $conn->query($item_list);

                                        if ($result_list->num_rows > 0) {
                                            while ($row_items = $result_list->fetch_assoc()) {
                                                // Displaying items that are not active in the promo table
                                                ?>
                                                <option class="form-control" value="<?php echo $row_items['product_id'] ?>">
                                                    <?php echo $row_items['product_name'] ?>
                                                </option>
                                                <?php
                                            }
                                        }

                      ?>
                      </select>
                      <div id="product_details_selected" class="my-4"></div>
                    </div>
                    <div class="layout-button flex-end">
              <button type="submit" class="btn btn-shadow-third btn-transparent-warnings " name="addpromo">Set Promo</button>
              <!-- <button type="button" id="promos_bundles" class="btn btn-shadow-white btn-transparent-purple" data-toggle="modal" data-target="#promo_bundle" onclick="bundle()">Promo Bundle</button> -->
              </div>
                  </div>
                  </form>
              </div>
          </div>
      </div>
      
<!-- bundle -->
<div class="modal fade" id="promo_bundle" tabindex="-1" role="dialog" aria-labelledby="promoBundleLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="promo_bundleLabel">Promo Bundle | Cuyapo Food Hub</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
                  </div>
                  <div class="modal-body">
       
    </div>
          </div>
      </div>


  </div>

  <div class="modal fade" id="view_terms" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="view_termsLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="view_termsLabel">Term and Policies | Cuyapo Food Hub</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
                  </div>
                  <div class="modal-body">
        <div class="form-group " style="height: 400px;">
                <textarea readonly class="form-control position-relative overflow-auto" id="text_area" style="height: 100%; resize: none;"><?php 
                if( !empty($row['terms_policies'])){
                  echo $row['terms_policies']; 
                } else {
                  echo '<div>No Policies and Terms Set</div>';
                }
                ?></textarea>
        </div>
    </div>
          </div>
      </div>


  </div>
    <div class="modal fade" id="update_terms" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="update_termsLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="update_termsLabel">Term and Policies | Cuyapo Food Hub</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
                  </div>
                  <form method="POST" action="edit_data">
                  <div class="modal-body">
                      <div class="form-group " style="height: 400px;">
                      <textarea class="form-control position-relative overflow-auto" name="terms_policy" style="height: 100%; resize: none;">
                      <?php 
                      if(!empty($row['terms_policies'])){
                          echo trim($row['terms_policies']);
                          
                      }else {
                        echo 'No set Policy yet.';
                      }?>
                      </textarea>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <div class="layout-button mt-25">
                        <button type="submmit" name="submit_data_term" class="btn btn-danger" >Update</button>
                      </div>
                  </div>
                  </form>
              </div>
          </div>
      </div>

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
    function select_item() {
      var button_with = document.getElementById("promos_bundles");
      var list_selection = document.getElementById("selections").value;
      $.ajax({
        url: "updata",
        method: "POST",
        data: {
          item_selected: list_selection
        },
        success: function(data) {
          $("#product_details_selected").html(data);
        }
      })
      button_with.classList.add('d-none');

    }
  </script>
      <script>
            function bundle(){
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