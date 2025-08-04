
<?php
include('../connection.php');

if (isset($_POST['product_id'])) {
    $product_id = trim($_POST['product_id']);

    $sql = "SELECT * FROM inventory WHERE product_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $item_price = $row['product_price'];

        echo '
<div class="row col-md-12 justify-content-center">
    <input type="hidden" value="' .$row['product_id'] . '" name="product_id" class="form-control">
    <input type="hidden" value="' . $row['product_qty'] . '" name="product_qty" class="form-control">
    <div class="form-group mx-2 my-2 col-md-5">
        <label class="my-2" for="name"> <span class="title ml-1">Product Quantity: <span class="text-secondary">' . number_format($row['product_qty']) . '</span></span> </label>
        <input type="number" class="form-control py-2" placeholder="add quantity" name="minus_qty" required>
    </div>

    <div class="form-group mx-4 my-2 col-md-5">
        <label class="my-2" for="status"> <span class="title">Product Price</span></label>';

        $sql_promos = "SELECT * FROM promo WHERE promo_status = 'active' AND item_id = ?";
        $promo_stmt = $conn->prepare($sql_promos);
        $promo_stmt->bind_param("s", $product_id);
        $promo_stmt->execute();
        $promo_result = $promo_stmt->get_result();

        if ($promo_result->num_rows > 0) {
            while ($rows_dp = $promo_result->fetch_assoc()) {
                echo '<input type="number" class="form-control py-2" value="' . $rows_dp['item_current_price']. '" name="minus_price" readonly>';
                echo '<span class="text-danger">*Currently in Promo.</span>';
            }
        } else {
            echo '<input type="number" class="form-control py-2" value="' .$row['product_price'] . '" name="minus_price" readonly>';
        }

        echo '
    </div>
</div>
<div class="row col-md-12 justify-content-center">
    <div class="form-group mx-2 my-2 col-md-5">
        <label class="my-2" for="name"> <span class="title"> Date In</span> </label>
        <input type="date" name="date_in" class="form-control py-2" value="' . $row['date_in'] . '" readonly>
    </div>
    <div class="form-group mx-4 my-2 col-md-5">
        <label class="my-2" for="expdate"> <span class="title">Expiration Date2</span></label>
        <input type="date" class="form-control py-2" name="date_exp" value="' .$row['exp_date'] . '">
    </div>
</div>
<div class="form-group mx-4 my-2">
    <label for="desc">Description</label>
    <textarea name="minus_prod" id="" class="form-control">' . $row['product_description'] . '</textarea>
</div>
';
    }

    $stmt->close();
} 


if(isset($_POST['item_selected'])){
    $item_selected = $_POST['item_selected'];
    $item_selected = trim($item_selected);

    $item_d = "SELECT * FROM inventory  WHERE product_id = '{$item_selected}'";
    $result_selected = $conn->query($item_d);

    while($rows_d = mysqli_fetch_array($result_selected)) {

        echo '<div class="d-flex gap-2">
        <div class="form-group">
        <label for="promo_price">Promo Price</label>
        <input type="number" class="form-control ih-medium ip-gray radius-xs b-light" name="item_promo_price" required>
        </div>
        <div class="form-group">
        <label for="current_price">Current Price</label>
        <input type="number" class="form-control ih-medium ip-gray radius-xs b-light" name="item_current_price" value="'.$rows_d['product_price'].'" readonly>
        </div>
        <div class="form-group">
        <label for="promo_price">Promo Expiration</label>
        <input type="datetime-local" class="form-control ih-medium ip-gray radius-xs b-light" name="promo_exp" required>
        </div>
        </div>';
    }
}

if(isset($_POST['addpromo'])){
    $promo_name = $_POST['promo_name'];
    $item_id = $_POST['item_id'];
    $item_promo_price = $_POST['item_promo_price'];
    $item_current_price = $_POST['item_current_price'];
    $promo_time = date('Y-m-d H:i:s', strtotime($_POST['promo_exp']));
  
    $exist_promo = "SELECT * FROM promo WHERE promo_name = '$promo_name'";
    $result = $conn->query($exist_promo);
  
    if($result->num_rows > 0){
        $update_status = "UPDATE promo SET promo_status = 'active' WHERE item_id = '$item_id'";
        $result_update = $conn->query($update_status);
        if($result_update == TRUE){
            echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire({
                      title: "Product Exist, Promo Activated.",
                      text: "Active Promo.",
                      icon: "success"
                  });
              });
              </script>';
              header('Location: profile.php');
        } 
      
    }   else {
        $insert_new = "INSERT INTO promo (promo_name, item_id, item_current_price, item_promo_price, active_promo) VALUES (?,?,?,?,?)";
        $result_new = $conn->prepare($insert_new);
        $result_new->bind_param('siiis',$promo_name, $item_id, $item_promo_price, $item_current_price, $promo_time);
  
        if($result_new->execute()){
            $promo_id = $conn->insert_id;

            $update_state = "UPDATE promo SET promo_status = 'active' WHERE promo_id = '$promo_id'";
            $result_state = $conn->query($update_state);
            if($result_state){
                echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "New Promo Active.",
                        text: "New Promo.",
                        icon: "success"
                    });
                });
                </script>';
               header('Location: profile.php');
            }
          
        } else {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "Promo Set-up Failed.",
                        text: "try to set again",
                        icon: "error"
                    });
                });
                </script>';
        }
    }
    // $result_state->close();
  }

  if(isset($_POST['delete_promo'])){
    $product_id = $_POST['promo_id'];
    $not_active = "not";
    $sql = "SELECT * FROM promo WHERE promo_id = '$product_id'";
    $result_d = $conn->query($sql);

    if($result_d->num_rows > 0){
        $update_status = "UPDATE promo SET promo_status = '$not_active' WHERE promo_id = '$product_id'";
        $result_update = $conn->query($update_status);
        if( $result_update == TRUE){
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "Promo Deleted.",
                        text: "Set more promo(s).",
                        icon: "success"
                    });
                });
                </script>';
                header('Location: profile.php');
        }
    }
  }

if(isset($_POST['active_items'])){
    $product_id = $_POST['product_id'];
    $item_name = $_POST['name_deleted'];
    $item_qty = $_POST['qty_deleted'];
    $item_price = $_POST['price_deleted'];
    $item_date_in = $_POST['date_in'];
    $item_exp = $_POST['expdate'];
    $item_desc = $_POST['desc_delete'];
    $state = 0;

    $item_check = "SELECT * FROM inventory WHERE product_id = ? AND product_name = ?";
    $result_check = $conn->prepare($item_check);
    $result_check->bind_param("is",  $product_id, $item_name);
    $result_check->execute();

    $final_result = $result_check->get_result();
    if($final_result->num_rows > 0){
        $update_item = "UPDATE inventory SET product_name = ?, product_qty = ?, product_price = ?, date_in = ?, exp_date = ?, state = ?, product_description = ? WHERE product_id = ?";
        $result_updates = $conn->prepare($update_item);
        $result_updates->bind_param("siissisi", $item_name, $item_qty,$item_price,$item_date_in, $item_exp, $state, $item_desc, $product_id);
       
        if($result_updates->execute()){
            echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Product Update Successfully.",
                    text: "Set more product.",
                    icon: "success"
                });
            });
            </script>';
            header('Location: inventory.php');
            exit();
        }
    } else {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Failed to update Product.",
                    text: "there is something wrong.",
                    icon: "error"
                });
            });
            </script>';
    }
;}