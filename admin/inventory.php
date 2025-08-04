<?php
include('sidebar.php');


?>

<?php
if (isset($_POST['addproduct'])) {
  $category_id = $_POST['category_id'];
  $product_name = $_POST['addname'];
  $product_qty = $_POST['addqty'];
  $product_price = $_POST['addprice'];
  $exp_date = $_POST['addexpdate'];
  $product_desc = $_POST['desc'];
  $status = $_POST['status'];
  $product_img = $_FILES['images'];

  // Prepare the SQL statement
  $insertproduct = "INSERT INTO inventory (product_category, product_name, product_qty, product_price, date_in, exp_date, product_description) VALUES ('$category_id', '$product_name','$product_qty', '$product_price', NOW(), '$exp_date', '$product_desc')";

  $result = $conn->query($insertproduct);

  if ($result) {
    $product_id = $conn->insert_id;
    if (!empty($product_img['name'])) {
      $targetDir = "../uploads/";
      $imageName = basename($product_img['name']);
      $targetFilePath = $targetDir . $imageName;

      if (move_uploaded_file($product_img['tmp_name'], $targetFilePath)) {

        $sql = "INSERT INTO product_img (product_id, img_name, availability) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $product_id, $imageName, $status);
        $stmt->execute();

        echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire({
                      title: "Product Added Successfully.",
                      text: "Nice!",
                      icon: "success"
                  });
              });
              </script>';
      } else {
        echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire({
                      title: "Error",
                      text: "Error uploading image.",
                      icon: "error"
                  });
              });
              </script>';
      }
    } else {
      echo "No image selected for upload.";
    }
  } else {
    echo "Error adding product: " . $conn->error;
  }
}

if (isset($_POST['usedproduct'])) {
  $qty = $_POST['product_qty'];
  $product_id = $_POST['product_id'];
  $less = $_POST['minus_qty'];

  $newqty = $qty - $less;

  $sql = "UPDATE inventory SET product_qty = ? WHERE product_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $newqty, $product_id);

  if ($stmt->execute()) {
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: "Recorded Successfully.",
            text: "Nice!",
            icon: "success"
          });
        });
    </script>';
  } else {
    echo "Error updating record: " . $stmt->error;
  }
}


if (isset($_POST['addto'])) {
  $qty = $_POST['product_qty'];
  $product_id = $_POST['product_id'];
  $less = $_POST['minus_qty'];
  $date_in = $_POST['date_in'];
  $date_exp = $_POST['date_exp'];
  // Calculate new quantity
  $newqty = $qty + $less;

  $sql = "UPDATE inventory SET product_qty = ?, exp_date = ?, date_in = NOW()  WHERE product_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iss", $newqty, $date_exp,  $product_id);

  if ($stmt->execute()) {
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: "Recorded Successfully.",
            text: "Nice!",
            icon: "success"
          });
        });
    </script>';
  } else {
    echo "Error updating record: " . $stmt->error;
  }
}

if (isset($_POST['update_state'])) {
  $state = $_POST['state'];
  $product_id = $_POST['product_id'];
  $status = "not";

  $sql_update = "UPDATE inventory SET state = '$state' WHERE product_id ='$product_id'";
  $result = $conn->query($sql_update);
  if ($result) {
    $update_pomo = "UPDATE promo SET promo_status = '$status' WHERE item_id = '$product_id'";
    $update_result = $conn->query($update_pomo);
    if ($update_result) {
      echo '<script>
      document.addEventListener("DOMContentLoaded", function() {
          Swal.fire({
              title: "Recorded Successfully.",
              text: "Nice!",
              icon: "success"
            });
          });
      </script>';
    } else {
      echo '<script>
      document.addEventListener("DOMContentLoaded", function() {
          Swal.fire({
              title: "Recorded Unsuccessfull.",
              text: "oh No!",
              icon: "error"
            });
          });
      </script>';
    }
  } else {
    echo '<script>
      document.addEventListener("DOMContentLoaded", function() {
          Swal.fire({
              title: "Recorded Unsuccessfull.",
              text: "oh No!",
              icon: "error"
            });
          });
      </script>';
  }
}

$items = "SELECT * FROM inventory WHERE state = 0";
$result_list = $conn->query($items);
?>
<div class="contents" style="background: rgb(252,250,241)">
  <div class="container-fluid">
    <div class="social-dash-wrap">
      <div class="row">
        <div class="col-lg-12">
          <div class="breadcrumb-main">
            <b class="text-capitalize breadcrumb-title px-2">Manage Inventory</b>
            <div class="breadcrumb-action justify-content-center flex-wrap">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Dashboard
                    </a></li>
                  <li class="breadcrumb-item active" aria-current="page">Manage Inventory</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 px-4">
          <div class="card" style="background:rgb(248,239,212)">
            <div class="card-body">
              <div class="userDatatable adv-table-table global-shadow border-light-0 w-100 adv-table">
                <div class="table-responsive">
                  <div class="adv-table-table__header">
                    <h4>Inventory</h4>
                    <div class="mx-1 my-2 d-flex justify-content-end w-full gap-4">
                      <button type="button" class="btn btn-info-hover" data-toggle="modal" data-target="#view_deleted"><i class="uil uil-document-info mx-1"></i>View Remove Product</button>
                      <?php
                      $items = "SELECT * FROM inventory WHERE state = 0";
                      $result_list = $conn->query($items);
                      if ($result_list) { ?>
                        <button type="button" class="btn text-white" data-toggle="modal" data-target="#exampleModal4" style="background:rgb(239, 90, 111)"><i class="uil uil-minus"></i>Product Used</button>
                        <button type="button" class="btn text-white" data-toggle="modal" data-target="#exampleModal3" style="background:rgb(160, 222, 255)"><i class="uil uil-plus"></i>Add Product</button>
                      <?php } else { ?>
                        <button type="button" class="btn text-white" data-toggle="modal" data-target="#exampleModal3"><i class="uil uil-plus"></i>Add Product</button>

                      <?php } ?>
                    </div>
                  </div>
                  <div id="filter-form-container">

                  </div>

                  <table class="table mb-0 table-borderless adv-table1" data-filter-container="#filter-form-container" data-paging-current="1" data-paging-position="right" data-paging-size="5">
                    <thead>
                      <tr class="userDatatable-header">
                        <th data-type="html" data-name="status">
                          <span class="userDatatable-title">Name</span>
                        </th>
                        <th>
                          <span class="userDatatable-title">Order Total</span>
                        </th>
                        <th>
                          <span class="userDatatable-title">Quantity</span>
                        </th>
                        <th>
                          <span class="userDatatable-title">price</span>
                        </th>
                        <th>
                          <span class="userDatatable-title">Date In</span>
                        </th>

                        <th>
                          <span class="userDatatable-title">Exp Date</span>
                        </th>

                        <th>
                          <span class="userDatatable-title">Description</span>
                        </th>

                        <th>
                          <span class="userDatatable-title">Action</span>
                        </th>

                      </tr>
                    </thead>
                    <tbody>
                      <?php


                      $items_added = "SELECT *, i.*, 
                       (SELECT SUM(item_qty) 
                        FROM order_items oi 
                        WHERE oi.product_id = i.product_id) AS name_count 
                FROM inventory i 
                WHERE i.state = 0";
                      $item_inventory = $conn->query($items_added);

                      if ($item_inventory) {
                        while ($row = $item_inventory->fetch_assoc()) {
                          $product_name = $row['product_name'];
                          $inv_product_qty = $row['product_qty'];
                          $oi_qty = $row['name_count'];
                          $product_id = $row['product_id'];

                          // Query to get the over_qty from the over_items table
                          $over_qty_query = "SELECT SUM(over_qty) AS over_qty 
                           FROM over_items 
                           WHERE over_name = '$product_name' 
                           OR item_id = '$product_id'";
                          $over_qty_result = $conn->query($over_qty_query);

                          if ($over_qty_result) {
                            $over_qty_row = $over_qty_result->fetch_assoc();
                            $over_qty = $over_qty_row['over_qty'] ?? 0;

                            $oi_qty += $over_qty;
                          }

                          $item_left = $inv_product_qty - $oi_qty;
                          echo "<tr>";
                          if ($item_left <= 50) {
                            echo "<td><span class='px-sm-2 text-danger position-absolute start-0 left-0 h-full'><i class='uil uil-bookmark-full'></i></span><div class='userDatatable-content position-relative mx-1'>" . $product_name . "</div></td>";
                          } else {
                            echo "<td><div class='userDatatable-content'>" . $product_name . "</div></td>";
                          }

                          echo "<td><div class='d-flex'><div class='userDatatable-inline-title'><a href='#' class='text-dark fw-500'><h6>" . $oi_qty . "</h6></a></div></div></td>";
                          echo "<td><div class='d-flex'><div class='userDatatable-inline-title'><a href='#' class='text-dark fw-500'><h6>" . number_format($item_left) . "/" . $inv_product_qty . "</h6></a></div></div></td>";
                          $sql_promo = "SELECT * FROM promo WHERE item_id = '$product_id' AND promo_status = 'active'";
                          $result_promos = $conn->query($sql_promo);

                          if ($result_promos->num_rows > 0) {
                            while ($row_of = $result_promos->fetch_assoc()) {
                              $product_id_new = $row['product_id'];

                              echo "<td><div class='userDatatable-content'>" . number_format($row_of["item_current_price"]) . "</div></td>";
                            }
                          } else {
                            echo "<td><div class='userDatatable-content'>" . number_format($row["product_price"]) . "</div></td>";
                          }
                          echo "<td><div class='userDatatable-content'>" . $row["date_in"] . "</div></td>";
                          echo "<td><div class='userDatatable-content'>" . $row["exp_date"] . "</div></td>";
                          echo "<td><div class='userDatatable-content'>" . $row["product_description"] . "</div></td>";

                          echo "<td class='col-lg-2'><div class='userDatatable-content row gap-1'>
                          <div>
                            <button class='btn btn-sm btn-info-hover' type='button' data-toggle='modal' data-target='#view_selecteds" . $row['product_id'] . "'  onclick='console.log(\"view_selecteds" . $row['product_id'] . "\")'><i class='uil uil-arrow-up-right'></i>Edit</button>
                        </div>
                <form method='POST'>
                <input type='hidden' value='" . $row['product_id'] . "' name='product_id'>
                <input type='hidden' value='1' name='state'>
                <button type='submit' class='btn btn-sm btn-danger text-center text-white mx-0' name='update_state' style='background:rgb(255, 105, 105)'><i class='uil uil-trash'></i> Delete</button>
                </form>
              </div></td>";

                          echo "</tr>";

                          echo '<div class="modal fade" id="view_selecteds' . $row['product_id'] . '" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="view_selecteds' . $row['product_id'] . '" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">';
                          $sql_view = "SELECT * FROM inventory WHERE product_id = '$product_id' AND state = 0";
                          $result_view = $conn->query($sql_view);

                          while ($row_view = $result_view->fetch_assoc()) {
                            $item_id_new = $row_view['product_id'];
                            $item_name_new = $row_view['product_name'];
                            $item_qty_new = $row_view['product_qty'];
                            $item_price_new = $row_view['product_price'];
                            $item_exp_new = $row_view['exp_date'];
                            $item_desc_new = $row_view['product_description'];
                          }

                          echo '<h5 class="modal-title" id="view_seleteds' . $row['product_id'] . '">Edit Product</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body px-md-4">
                                      <form method="POST" action="deletedlist">
                                      <input type="hidden" value="' . $item_id_new . '" name="view_id">
                                        <div class="form-group mx-4 my-2">
                                          <label class="my-2" for="name"> <span class="title">Product Name</span> </label>
                                          <input type="text" class="form-control py-2" name="view_name" value="' . $item_name_new . '">
                                        </div>
                                          <div class="row col-md-12 justify-content-center">
                                            <div class="form-group mx-2 my-2 col-md-5">
                                              <label class="my-2" for="qty"> <span class="title">Product Quantity</span> </label>
                                              <input type="number" class="form-control py-2" name="view_qty" value="' . $item_qty_new . '">
                                            </div>
                                            <div class="form-group mx-4 my-2 col-md-5">
                                              <label class="my-2" for="price"> <span class="title">Product Price</span></label>';
                          $sql_check_promo = "SELECT * FROM promo WHERE item_id = '$item_id_new' AND promo_status = 'active'";
                          $result_of = $conn->query($sql_check_promo);
                          if ($result_of->num_rows > 0) {
                            while ($rows_of = $result_of->fetch_assoc()) {
                              echo ' <input type="number" class="form-control py-2" name="view_price" value="' . $rows_of['item_current_price'] . '">';
                            }
                          } else {
                            echo ' <input type="number" class="form-control py-2" name="view_price" value="' . $item_price_new . '">';
                          }

                          echo '</div>
                                          </div>
                                          <div class="form-group mx-4 my-2">
                                            <label class="my-2" for="expdate"> <span class="title">Expiration Date</span></label>
                                            <input type="date" class="form-control py-2" name="view_exp" value="' . $item_exp_new . '">
                                          </div>
                                          <div class="form-group mx-4 my-2">
                                            <label for="adddesc">Description</label>
                                            <textarea class="form-control" name="view_desc" >' . $item_desc_new . '</textarea>
                                          </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="submit" class="btn btn-secondary" name="view_edit_save">Save Product</button>
                                        <button type="submit" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                        <!-- Additional buttons or actions can be added here -->
                                      </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>';
                        }
                      } else {
                        echo "0 results";
                      }



                      ?>

                    </tbody>

                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>




  <!-- minus -->
  <div class="modal fade" id="exampleModal4" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel4" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel4">Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-md-4">
          <form method="POST">
            <div class="form-group mx-4 my-2">
              <label class="my-2" for="name"> <span class="title">Product List</span> </label>
              <select name="minus_name" id="minus_prod" onchange="usedProduct()" class="form-control">
                <option disabled selected>Select Product</option>

                <?php
                $data_update = "SELECT product_name FROM inventory WHERE state = 0";
                $result = $conn->query($data_update);

                if ($result) {
                  while ($row = $result->fetch_assoc()) {
                ?>

                    <option value="<?php echo $row['product_name']; ?>"><?php echo $row['product_name']; ?></option>

                <?php
                  }
                } else {
                  echo '<option value="" disabled>No products found</option>';
                }
                ?>
              </select>
            </div>
            <div id="form_data" class="row col-md-12 justify-content-center">

            </div>
        </div>
        <div class="modal-footer">
          <?php if ($result->num_rows != 0) { ?>
            <button type="submit" class="btn btn-secondary d-none" id="use_btn" name="usedproduct">Use</button>
          <?php } else { ?>
            <button type="submit" class="btn btn-primary " name="usedproduct" disabled>Use</button>
          <?php }  ?>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!--add-->
  <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel3">Add Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-md-4">
          <form method="POST">
            <div class="form-group">
              <label class="my-sm-2" for="">Product</label>
              <select class="form-control w-100" name="product_name" id="product_list" onchange="addproducts()">
                <option selected disabled>Select Product</option>
                <?php
                $sql = "SELECT product_name FROM inventory WHERE state = 0";
                $result = $conn->query($sql);

                if ($result) {
                  while ($row = $result->fetch_assoc()) {
                ?>

                    <option value="<?php echo $row['product_name']; ?>"><?php echo $row['product_name']; ?></option>

                <?php
                  }
                } else {
                  echo '<option disabled>No products found</option>';
                }
                ?>
              </select>
            </div>
            <div id="product_details_container">

            </div>

            <div class="modal-footer d-flex align-items-stretch">

              <?php if ($result->num_rows == 0) { ?>
                <div class="form-group">
                  <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal34"><i class="uil uil-plus"></i>New Product</button>
                </div>
              <?php } else { ?>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary d-none" id="add_prod" name="addto">Add</button>
                </div>
                <div class="form-group">
                  <button type="button" class="btn btn-warning" id="newButton" data-toggle="modal" data-target="#exampleModal34"><i class="uil uil-plus"></i>New Product</button>
                </div>
              <?php } ?>


            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!--  new-->
  <div class="modal fade" id="exampleModal34" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel34" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel34">Add Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-md-4">
          <form method="POST" enctype="multipart/form-data">
            <div class="form-group mx-4 my-2">
              <label class="my-2" for="name"> <span class="title">Product Categry</span> </label>
              <select class="form-control w-100" name="category_id" id="new_listed" onchange="newProdcut()">
                <option value="" selected disabled>Select Product</option>
                <?php
                $sql = "SELECT * FROM menu WHERE status = 'available'";
                $result = $conn->query($sql); // Execute the SQL query

                if ($result) {
                  while ($row1 = $result->fetch_assoc()) {
                ?>

                    <option value="<?php echo $row1['category_id']; ?>"><?php echo $row1['category']; ?></option>

                <?php
                  }
                } else {
                  echo '<option disabled>No products found</option>';
                }
                ?>
              </select>
            </div>
            <div id="new_listed_product">

            </div>
            <div class="form-group mx-4 my-2">
              <label class="my-2" for="name"> <span class="title">Product Name</span> </label>
              <input type="text" class="form-control py-2" name="addname" required>
            </div>
            <div class="form-group mx-4 my-2">
              <label class="my-2" for="name"> <span class="title">Availability</span> </label>
              <select name="status" class="form-control" required>
                <option disabled selected>Select Status</option>
                <option value="available">Available</option>
                <option value="Navailable">Not available</option>
              </select>
            </div>
            <div class="row col-md-12 justify-content-center">
              <div class="form-group mx-2 my-2 col-md-5">
                <label class="my-2" for="qty"> <span class="title">Product Quantity</span> </label>
                <input type="number" class="form-control py-2" name="addqty" required>
              </div>
              <div class="form-group mx-4 my-2 col-md-5">
                <label class="my-2" for="price"> <span class="title">Product Price</span></label>
                <input type="number" class="form-control py-2" name="addprice" required>
              </div>
            </div>
            <div class="form-group mx-4 my-2">
              <label class="my-2" for="expdate"> <span class="title">Expiration Date</span></label>
              <input type="date" class="form-control py-2" name="addexpdate" required>
            </div>
            <div class="col-md-12 px-md-3">
              <div class="form-group">
                <label for="a8" class="il-gray fs-14 fw-500 align-center mb-10">Images</label>
                <div class="dm-upload">
                  <div class="dm-upload-avatar media-import dropzone-md-s">
                    <p class="color-light mt-0 fs-14">Drop files here to upload</p>
                  </div>
                  <div class="avatar-up">
                    <input type="file" name="images" class="upload-avatar-input" id="uploadInput" multiple accept="image/*" required>

                  </div>
                </div>
              </div>
              <div class="dm-upload__file">
                <ul id="imageList">
                  <!-- Uploaded image names will be displayed here -->
                </ul>
              </div>
            </div>
            <div class="form-group mx-4 my-2">
              <label for="adddesc">Description</label>
              <textarea class="form-control" name="desc" required></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-secondary" name="addproduct">Add Product</button>
          <!-- Additional buttons or actions can be added here -->
        </div>
        </form>
      </div>
    </div>
  </div>


  <!-- deleted -->

  <div class="modal fade" id="view_deleted" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="view_deletedLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="view_deletedLabel">Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-md-4">
          <form method="POST" action="updata">
            <div class="form-group mx-4 my-2">
              <label class="my-2" for="name"> <span class="title">Product List</span> </label>
              <select name="delete_item" id="delete_product" onchange="viewDeleteProduct()" class="form-control">
                <option disabled selected>Select Product</option>

                <?php
                $data_update = "SELECT product_name FROM inventory WHERE state = 1";
                $result = $conn->query($data_update);

                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                ?>

                    <option value="<?php echo $row['product_name']; ?>"><?php echo $row['product_name']; ?></option>

                <?php
                  }
                } else {
                  echo '<option value="" disabled>No products found</option>';
                }
                ?>
              </select>
            </div>
            <div id="form_deleted" class="row col-md-12 justify-content-center">

            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="active_items" class="btn btn-shadow-success btn-dark-hover d-none" id="active_view">Active Product</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- views -->


  <div class="modal fade" id="view_selecteds<?php echo $product_id; ?>" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModal100<?php echo $product_id; ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModal100<?php echo $product_id; ?>">Add Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-md-4">
          <form method="POST">
            <div class="form-group mx-4 my-2">
              <label class="my-2" for="name"> <span class="title">Product Name</span> </label>
              <input type="text" class="form-control py-2" name="addname">
            </div>
            <div class="row col-md-12 justify-content-center">
              <div class="form-group mx-2 my-2 col-md-5">
                <label class="my-2" for="qty"> <span class="title">Product Quantity</span> </label>
                <input type="number" class="form-control py-2" name="addqty">
              </div>
              <div class="form-group mx-4 my-2 col-md-5">
                <label class="my-2" for="price"> <span class="title">Product Price</span></label>
                <input type="number" class="form-control py-2" name="addprice">
              </div>
            </div>
            <div class="form-group mx-4 my-2">
              <label class="my-2" for="expdate"> <span class="title">Expiration Date</span></label>
              <input type="date" class="form-control py-2" name="addexpdate">
            </div>
            <div class="form-group mx-4 my-2">
              <label for="adddesc">Description</label>
              <textarea class="form-control" name="desc"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-secondary" name="addproduct">Add Product</button>
          <!-- Additional buttons or actions can be added here -->
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
  <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="./js/sweetalert2.all.min.js"></script>


  <script>
    document.getElementById('uploadInput').addEventListener('change', handleFileSelect, false);

    function handleFileSelect(event) {
      const files = event.target.files;
      const list = document.getElementById('imageList');
      list.innerHTML = '';
      for (let i = 0; i < Math.min(files.length, 5); i++) {
        const file = files[i];
        const listItem = document.createElement('li');
        const fileName = document.createElement('span');
        fileName.textContent = file.name;
        const deleteBtn = document.createElement('a');
        deleteBtn.className = 'btn-delete';
        deleteBtn.innerHTML = '<i class="la la-trash"></i>';
        deleteBtn.addEventListener('click', function() {
          listItem.remove();
        });
        listItem.appendChild(fileName);
        listItem.appendChild(deleteBtn);
        list.appendChild(listItem);
      }
    }
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
              class: "form-group dm-select d-none align-items-center adv-table-searchs__status my-xl-25 my-15 mb-0 me-sm-30 me-0"
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


  <!--  -->
  <script>
    function usedProduct() {
      var datafetch = document.getElementById("minus_prod").value;
      var btn_use = document.getElementById("use_btn");
      $.ajax({
        url: "update",
        method: "POST",
        data: {
          product_id: datafetch
        },
        success: function(data) {
          $("#form_data").html(data);
        }
      })
      btn_use.classList.remove("d-none");
    }
  </script>

  <script>
    function addproducts() {
      var itemlist = document.getElementById("product_list").value;
      var newButton = document.getElementById("newButton");
      let add_prod = document.getElementById('add_prod');
      $.ajax({
        url: "updata",
        method: "POST",
        data: {
          product_id: itemlist
        },
        success: function(data) {
          $("#product_details_container").html(data);
        }
      })
      newButton.classList.add('d-none');
      add_prod.classList.remove('d-none');
    }
  </script>
  <script>
    function viewDeleteProduct() {
      const listDeleted = document.getElementById("delete_product").value;
      var active_btn = document.getElementById("active_view");
      $.ajax({
        url: "deletedlist",
        method: "POST",
        data: {
          product_name: listDeleted
        },
        success: function(data) {
          $("#form_deleted").html(data);
          console.log(data);
        }
      })
      active_btn.classList.remove("d-none");
    }
  </script>
  </body>


  </html>