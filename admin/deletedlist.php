<?php
include('../connection.php');

if(isset($_POST['product_name'])){
    $product_id = $_POST['product_name'];
  
    $select_details = "SELECT * FROM inventory WHERE product_name = ?";
    $result_select = $conn->prepare($select_details);
    $result_select->bind_param("s", $product_id);
    $result_select->execute();
    $result_select = $result_select->get_result();

        while($row = $result_select->fetch_assoc()){
            $product_id = $row['product_id'];
            echo '<div class="row col-md-12 justify-content-center">
    <input type="hidden" value="' .$row['product_id'] . '" name="product_id" class="form-control">
    <input type="hidden" value="' . $row['product_qty'] . '" name="product_qty" class="form-control">
    <div class="form-group">
        <label class="my-2" for="name"> <span class="title ml-1">Product Name:</span></label>
        <input type="text" class="form-control py-2" name="name_deleted" value="' . $row['product_name'] . '">
    </div>
    <div class="form-group mx-2 my-2 col-md-5">
        <label class="my-2" for="name"> <span class="title ml-1">Product Quantity: <span class="text-secondary"></span></span> </label>
        <input type="number" class="form-control py-2" name="qty_deleted" value="' . number_format($row['product_qty']) . '">
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
                echo '<input type="number" class="form-control py-2" value="' . $rows_dp['item_current_price']. '" name="price_deleted" readonly>';
                echo '<span class="text-danger">*Currently in Promo.</span>';
            }
        } else {
            echo '<input type="number" class="form-control py-2" value="' .$row['product_price'] . '" name="price_deleted">';
        }

        echo '
    </div>
</div>
<div class="row col-md-12 justify-content-center">
    <div class="form-group mx-2 my-2 col-md-5">
        <label class="my-2" for="name"> <span class="title"> Date In</span> </label>
        <input type="date" name="date_in" class="form-control py-2" value="' . $row['date_in'] . '">
    </div>
    <div class="form-group mx-4 my-2 col-md-5">
        <label class="my-2" for="expdate"> <span class="title">Expiration Date</span></label>
        <input type="date" class="form-control py-2" name="expdate" value="' .$row['exp_date'] . '">
    </div>
</div>
<div class="form-group mx-4 my-2">
    <label for="desc">Description</label>
    <textarea name="desc_delete" id="" class="form-control" >' . $row['product_description'] . '</textarea>
</div>';
  
        }
  
    
  }


//   view data set - edit

if(isset($_POST['view_edit_save'])){
    $item_id = $_POST['view_id'];
    $item_name = $_POST['view_name'];
    $item_qty = $_POST['view_qty'];
    $item_price = $_POST['view_price'];
    $item_exp = $_POST['view_exp'];
    $item_desc = $_POST['view_desc'];

    $check_item = "SELECT * FROM inventory WHERE product_id = ? AND state = 0";
    $result_check = $conn->prepare($check_item);
    $result_check->bind_param("i", $item_id);
    $result_check->execute();
    $result = $result_check->get_result();

    if($result->num_rows > 0){
        $up_view = "UPDATE inventory SET product_name = ?, product_qty = ?, product_price = ?, exp_date = ?, product_description = ? WHERE product_id = ?";
        $result_view = $conn->prepare($up_view);
        $result_view->bind_param("siissi", $item_name, $item_qty, $item_price, $item_exp, $item_desc,$item_id );

        if($result_view->execute()){
            echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Product Saved.",
                    text: "Good work.",
                    icon: "success"
                });
            });
            </script>';
            header('Location: inventory.php');
        }


    }
    $result_check->close();
}