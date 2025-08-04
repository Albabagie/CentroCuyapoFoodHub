<?php
include('sidebar.php');
$category_id = $_GET['category_id'];

$sql = "SELECT category FROM menu WHERE category_id = '$category_id'";
$result = $conn->query($sql);

$cat_name = $result->fetch_assoc();



function showError($msg) {
    echo '<script src="../sweetalert2.min.js"></script>';
    echo '<script>
      document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
          title: "Error",
          text: ' . json_encode($msg) . ',
          icon: "error",
          confirmButtonText: "OK"
        });
      });
    </script>';
    exit;
}

if (isset($_POST['add_otc'])) {
  $item_name = $_POST['item_name'];
  $item_price = $_POST['item_price'];
  $item_id = $_POST['item_id'];
  $item_desc = $_POST['item_desc'];
  $item_qty = 1;

  $sql_exist = "SELECT * FROM otc_item oi LEFT JOIN otc o ON oi.otc_id = o.otc_id WHERE oi.otc_void = 0 AND oi.otc_order = 0 AND o.otc_status = 0 AND oi.item_id = ? AND o.employee_id = ?";
  $stmt_exist = $conn->prepare($sql_exist);
  if (!$stmt_exist) {
    showError("Prepare failed (exist): " . $conn->error);
  }
  $stmt_exist->bind_param("si", $item_id, $id);
  if (!$stmt_exist->execute()) {
    showError("Execute failed (exist): " . $stmt_exist->error);
  }
  $result_exist = $stmt_exist->get_result();

  if ($result_exist->num_rows > 0) {
    $row_exist = $result_exist->fetch_assoc();
    $existing_otc_id = $row_exist['otc_id'];

    $update_qty_sql = "UPDATE otc_item SET otc_qty = otc_qty + ? WHERE otc_id = ?";
    $stmt_update_qty = $conn->prepare($update_qty_sql);
    if (!$stmt_update_qty) {
      showError("Prepare failed (update qty): " . $conn->error);
    }
    $stmt_update_qty->bind_param("ii", $item_qty, $existing_otc_id);
    if (!$stmt_update_qty->execute()) {
      showError("Execute failed (update qty): " . $stmt_update_qty->error);
    }

    echo '<script src="../sweetalert2.min.js"></script>';
    echo '
      <script>
        document.addEventListener("DOMContentLoaded", function() {
          Swal.fire({
            title: "Item Quantity Updated",
            text: "List of order updated.",
            icon: "info",
            timer: 2500,
            showConfirmButton: false
          });
        });
      </script>';
  } else {
    $sql_item = "INSERT INTO otc (item_id, employee_id) VALUES (?, ?)";
    $result_otc = $conn->prepare($sql_item);
    if (!$result_otc) {
      showError("Prepare failed (insert otc): " . $conn->error);
    }
    $result_otc->bind_param('si', $item_id, $id);
    if (!$result_otc->execute()) {
      showError("Execute failed (insert otc): " . $result_otc->error);
    }
    $otc_id = $result_otc->insert_id;

    $items_otc = "INSERT INTO otc_item (item_name, item_price, otc_qty, item_desc, item_id, employee_id, otc_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $item_otc = $conn->prepare($items_otc);
    if (!$item_otc) {
      showError("Prepare failed (insert otc_item): " . $conn->error);
    }
    $item_otc->bind_param('sdssiii', $item_name, $item_price, $item_qty, $item_desc, $item_id, $id, $otc_id);
    if (!$item_otc->execute()) {
      showError("Execute failed (insert otc_item): " . $item_otc->error);
    }

    echo '<script src="../sweetalert2.min.js"></script>';
    echo '
      <script>
        document.addEventListener("DOMContentLoaded", function() {
          Swal.fire({
            title: "Item Order Inserted",
            text: "List of order updated.",
            icon: "success",
            timer: 2500,
            showConfirmButton: false
          });
        });
      </script>';
  }
} 


// if (isset($_POST['add_otc'])) {
//   $item_name = $_POST['item_name'];
//   $item_price = $_POST['item_price'];
//   $item_id = $_POST['item_id'];
//   $item_desc = $_POST['item_desc'];

//   $item_qty = 1;

//   $sql_exist = "SELECT * FROM otc_item oi LEFT JOIN otc o ON oi.otc_id = o.otc_id WHERE oi.otc_void = 0 AND oi.otc_order = 0 AND oi.item_id = ? AND o.employee_id = ?";
//   $stmt_exist = $conn->prepare($sql_exist);
//   $stmt_exist->bind_param("si", $item_id, $id);
//   $stmt_exist->execute();
//   $result_exist = $stmt_exist->get_result();

//   if ($result_exist->num_rows > 0) {
//     $row_exist = $result_exist->fetch_assoc();
//     $existing_otc_id = $row_exist['otc_id'];

//     $update_qty_sql = "UPDATE otc_item SET otc_qty = otc_qty + ? WHERE otc_id = ?";
//     $stmt_update_qty = $conn->prepare($update_qty_sql);
//     $stmt_update_qty->bind_param("ii", $item_qty, $existing_otc_id);
//     if ($stmt_update_qty->execute()) {
//       // header('Location: otc.php');
//       echo '<script src="../sweetalert2.min.js"></script>';
//       echo '
//       <script>
//   document.addEventListener("DOMContentLoaded", function() {
//     Swal.fire({
//       title: "Item Quantity Order Added",
//       text: "List of order updated.",
//       icon: "info",
//       timer: 2500,
//       showConfirmButton: false
//     });

//     setTimeout(function() {
//     }, 2600); // Redirect slightly after alert closes
//   });
// </script>';
//     }
//   } else {
//     $sql_item = "INSERT INTO otc (item_id, employee_id) VALUES (?,?)";
//     $result_otc = $conn->prepare($sql_item);
//     $result_otc->bind_param('si', $item_id, $id);
//     if ($result_otc->execute()) {
//       $otc_id = $result_otc->insert_id;
//       $items_otc = "INSERT INTO otc_item (item_name, item_price, otc_qty, item_desc, item_id, employee_id, otc_id) VALUES (?,?,?,?,?,?,?)";
//       $item_otc = $conn->prepare($items_otc);
//       $item_otc->bind_param('ssssiii', $item_name, $item_price, $item_qty, $item_desc, $item_id, $id, $otc_id);
//       if ($item_otc->execute()) {
//         // header('Location: otc.php');
//         echo '<script src="../sweetalert2.min.js"></script>';
//         echo '<script>
//   document.addEventListener("DOMContentLoaded", function() {
//     Swal.fire({
//       title: "Item Order Inserted",
//       text: "List of order updated.",
//       icon: "success",
//       timer: 2500,
//       showConfirmButton: false
//     });

//     setTimeout(function() {
//     }, 2600); // Redirect after 2.6 seconds
//   });
// </script>';
//       }
//     }
//   }
// }

?>



<div class="contents" style="background:rgb(252,250,241)">
  <div class="container-fluid ">
    <div class="row">
      <div class="col-lg-12">
        <div class="shop-breadcrumb">
          <div class="breadcrumb-main">
            <h4 class="text-capitalize breadcrumb-title">Listed Item</h4>
            <div class="breadcrumb-action justify-content-center flex-wrap">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Order Over</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Menu</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="products mb-3">
    <div class="ccontainer-fluid">
      <div class="card product-details border-0">
        <h class="card-header w-100 d-flex justify-content-center" style="background:rgb(47, 54, 69)">
          <h4 class="text-white"><?php echo $cat_name['category'] ?></h4>
        </h>
        <div class="product-item p-sm-20" style="background:rgb(238, 237, 235)">
          <div class="row flex-wrap col-sm-4 col-md-12">
            <!-- container -->
            <?php
            $sql = "SELECT * FROM inventory i LEFT JOIN product_img pi ON pi.product_id = i.product_id WHERE product_category = '$category_id' AND i.state = 0";
            $res = $conn->query($sql);

            if ($res->num_rows > 0) {
              while ($row = $res->fetch_assoc()) {
                $product_id = $row['product_id'];
                if ($row['availability'] == 'available') {
                  echo '<form method="POST"  class="col-lg-4 col-md-6" style="">';
                  echo '<div class="card my-sm-2 mb-4"style="background: rgb(248,239,212);box-shadow: rgba(17, 17, 26, 0.1) 0px 8px 24px, rgba(17, 17, 26, 0.1) 0px 16px 56px, rgba(17, 17, 26, 0.1) 0px 24px 80px;">';
                  echo '<div class="card-header" style="background:rgb(248,239,212);">
                              <div class="fw-500 text-dark">
                              <span class="fw-500 mx-md-4 text-end">' . $row['product_name'] . '</span>
                              </div>
                            <div class="fw-500 text-dark">
                            <span class="px-sm-2 fw-500 text-dark border border-warning rounded" style="font-size:18px;">₱ ' . number_format($row['product_price']) . '</span>
                            </div>
                       </div>';
                  echo '<div class="ratio ratio-4x3">';
                  echo '<img src="../uploads/' . $row['img_name'] . '" class="card-img-top p-2">';
                  echo '</div>';
                  if ($row['availability'] == 'available') {
                    echo "<div class='rounded-pill bg-success text-center text-capitalize text-white my-2 mx-1'>" . $row['availability'] . "</div>";
                  } else {
                    echo "<di class='rounded-pill bg-danger text-center '>" . $row['availability'] . "</di>";
                  }
                  echo '<div class="card-body rounded p-2">
                             <div class="text-dark">' . $row['product_description'] . '</div>
                       </div>
                       <div class="card-footer" style="background:rgb(229, 153, 52);">
                       <input type="hidden" value="1" name="item_qty">
                       <input type="hidden" value="' . $row['product_name'] . '" name="item_name">
                       <input type="hidden" value="' . $row['product_id'] . '" name="item_id">
                       <input type="hidden" value="' . $row['product_price'] . '" name="item_price">
                       <input type="hidden" value="' . $row['product_description'] . '" name="item_desc">
                       <button type="submit" class="btn btn-square text-white w-100" name="add_otc" style="background:rgb(19, 39, 67)">Add</button>
                       </div>
                      </div>
                        ';
                  echo '</form>';
                } else {
                  echo '
                      <div class="card col-lg-3 mx-lg-4 my-lg-4  border-1 border-danger my-md-2">';
                  echo '<div class="card-header position-relative">
                              <h2 class="position-absolute">' . $row['item_name'] . '<span class="position-relative top-0 rounded-bottom bg-warning text-white p-lg-3 fs-5">₱' . number_format($row['item_price']) . '</span></h2>
                            
                       </div>';
                  echo '<img src="../uploads/' . $row['item_img'] . '" class="card-img-top p-sm-2">';
                  if ($row['item_status'] == 'available') {
                    echo "<span class='rounded-pill bg-success text-center'>" . $row['item_status'] . "</span>";
                  } else {
                    echo "<span class='rounded-pill bg-danger text-center'>" . $row['item_status'] . "</span>";
                  }
                  echo '<div class="card-body rounded p-1">
                             <p>' . $row['item_description'] . '</p>
                       </div>
                       <div class="card-footer">
                           <a href="viewitem.php?item_id=' . $row['item_id'] . '"class="btn btn-secondary w-100" id="item_edit">View Item</a>
                       </div>
                      </div>
                        ';
                }
              }
            } else {
              echo "<div class='row justify-content-center'><img src='../img/svg/Questions-amico.svg' class='w-50 h-50' ><h2 class='w-100 text-center'>Empty</h2></div>";
            }


            ?>
          </div>



          <!-- end -->
        </div>
        <div class="mx-lg-5 col-lg-4">

          <div class="product-details__availability">

            <div class="product-item__button mt-lg-30 mt-sm-25 mt-20 d-flex flex-wrap">
              <div class=" d-flex flex-wrap product-item__action align-items-center">

                <!--  -->



              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- modals -->


<div class="modal fade" id="additemmenu" tabindex="-1" role="dialog" aria-labelledby="additemmenu" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="additemmenu">Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data">
          <div class="row">
            <div class="form-group col-lg-6">
              <label for="a3" class="il-gray fs-14 fw-500 align-center mb-10">Name</label>
              <input type="text" class="form-control ih-medium ip-light radius-xs b-light px-15" name="name"
                placeholder="Enter Item Name">
            </div>


            <div class="form-group mb-20 col-md-6">
              <label for="status" class="mb-2">Status</label>
              <select class="form-control form-control ih-medium ip-light radius-xs b-light px-15" name="status">
                <option value="available">Available</option>
                <option value="not">Not Available</option>
              </select>
            </div>

          </div>


          <div class="col-md-12 px-md-3">
            <div class="form-group">
              <label for="a8" class="il-gray fs-14 fw-500 align-center mb-10">Images</label>
              <div class="dm-upload">
                <div class="dm-upload-avatar media-import dropzone-md-s">
                  <p class="color-light mt-0 fs-14">Drop files here to upload</p>
                </div>
                <div class="avatar-up">
                  <input type="file" name="images[]" class="upload-avatar-input" id="uploadInput" multiple
                    accept="image/*">

                </div>
              </div>
            </div>
            <div class="dm-upload__file">
              <ul id="imageList">
              </ul>
            </div>
          </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="close">Cancel</button>

        <button type="submit" class="btn btn-secondary" name="additems">Save</button>
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


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="js/plugins.min.js"></script>
<script src="js/script.min.js"></script>


<script src="./js/sweetalert2.all.min.js"></script>



<script>
  const uploadedInput = document.getElementById('fileInput');
  const imgupload = document.getElementById('imgs');
  uploadedInput.addEventListener('change', function(event) {
    const fileUp = event.target.files[0];
    imgupload.src = URL.createObjectURL(fileUp);
  });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    var uploadInput = document.getElementById('uploadInput');
    var dropzone = document.querySelector('.dm-upload-avatar.media-import.dropzone-md-s');

    uploadInput.addEventListener('change', function() {
      var files = this.files;
      dropzone.innerHTML = ''; // Clear previous content

      for (var i = 0; i < files.length; i++) {
        var file = files[i];
        if (file.type.match('image.*')) {
          var reader = new FileReader();

          reader.onload = function(event) {
            var imgElement = document.createElement('img');
            imgElement.classList.add('uploaded-image');
            imgElement.src = event.target.result;
            imgElement.style.maxWidth = '300px';
            imgElement.style.maxHeight = '300px';
            dropzone.appendChild(imgElement);
          };

          reader.readAsDataURL(file);
        }
      }
    });
  });
</script>



</body>

</html>