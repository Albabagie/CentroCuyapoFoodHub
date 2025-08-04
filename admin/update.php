<?php

include('../connection.php');

if (isset($_POST['product_id'])) {
  $product_id = $_POST['product_id'];
  $product_id = trim($product_id);

  $sql = "SELECT * FROM inventory WHERE product_name = '{$product_id}'";
  $result = $conn->query($sql);

  if ($result) {
    while ($row = mysqli_fetch_array($result)) {
      $product_id = $row['product_id'];
      echo '
                <div class="row col-md-12 justify-content-center">
                    <input type="hidden" value="' . $row['product_id'] . '" name="product_id" class="form-control">
                    <input type="hidden" value="' . $row['product_qty'] . '" name="product_qty" class="form-control">
                    <div class="form-group mx-2 my-2 col-md-5">
                        <label class="my-2" for="name"> <span class="title ml-1">Product Quantity:<span class="text-secondary">' . number_format($row['product_qty']) . '</span></span> </label>
                        <input type="number" class="form-control py-2" placeholder="Less" name="minus_qty" required>
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
                        <label class="my-2" for="expdate"> <span class="title">Expiration Date</span></label>
                        <input type="date" class="form-control py-2" name="expdate" value="' . $row['exp_date'] . '" readonly>
                    </div>
                </div>
                <div class="form-group mx-4 my-2 ">
                    <label for="desc">Description</label>
                    <textarea name="minus_prod" id="" class="form-control">' . $row['product_description'] . '</textarea>
                </div>';
    }
  } else {
    echo '<div class="alert alert-danger">No product found</div>';
  }
} else {
  echo '<div class="alert alert-danger">Product ID is not set</div>';
}


