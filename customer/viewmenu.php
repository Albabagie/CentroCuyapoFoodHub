<?php
include('sidebar.php');

$category_id = $_GET['category_id'];

$category = $conn->query("SELECT category FROM menu WHERE category_id = '$category_id'");
$_category = $category->fetch_assoc()['category'];


if (isset($_POST["addtoorder"])) {
  $item_id = $_POST["item_id"];
  $item_name = $_POST["order_name"];
  $item_qty = $_POST["order_qty"];
  // $item_desc = $_POST["item_desc"];
  $item_price = $_POST["order_price"];



  // $item = $item_name . "-" . strtolower($item_desc);

  $check_sql = "SELECT * FROM cart_item ci LEFT JOIN cart c ON c.product_id = ci.product_id WHERE c.product_id = ? AND c.customer_id = ? AND ci.item_void = 0 AND ci.ordered_item = 0 AND c.cart_status = 0";
  $check_stmt = $conn->prepare($check_sql);
  $check_stmt->bind_param("ii", $item_id, $id);
  $check_stmt->execute();
  $check_result = $check_stmt->get_result();
  // resolve cart
  if ($check_result->num_rows > 0) {
    $existing_item = $check_result->fetch_assoc();
    $new_qty = $existing_item['qty'] + $item_qty;
    $update_sql = "UPDATE cart_item SET qty = ? WHERE product_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $new_qty, $existing_item['product_id']);
    if ($update_stmt->execute()) {

      echo '<script>
                  document.addEventListener("DOMContentLoaded", function() {
                      Swal.fire({
                          title: "Item quantity updated.",
                          text: "",
                          icon: "success"
                      });
                      setTimeout(function() {
                      }, 2000);
                  });
              </script>';
    } else {
      echo "Error updating item quantity: " . $conn->error;
    }
  } else {
    $sql_cart = "INSERT INTO cart (product_id, customer_id,order_date) VALUES (?,?,NOW())";
    $result_cart = $conn->prepare($sql_cart);
    $result_cart->bind_param("ii", $item_id, $id);
    if ($result_cart->execute()) {
      $cart_id = $result_cart->insert_id;
      $sql = "INSERT INTO cart_item (name, qty, price, customer_id, product_id, cart_id) VALUES (?, ?, ?, ?, ?, ?)";
      $result2 = $conn->prepare($sql);
      $result2->bind_param("ssiiii", $item_name, $item_qty, $item_price, $id, $item_id, $cart_id);
    }
    if ($result2->execute()) {

      echo '<script>
                  document.addEventListener("DOMContentLoaded", function() {
                      Swal.fire({
                          title: "Item added to your cart..",
                          text: "",
                          icon: "success"
                      });
                      setTimeout(function() {
                      }, 2000);
                  });
              </script>';
    } else {
      echo "Error adding item to cart item table: " . $conn->error;
    }
  }
}
?>
<div class="contents" style="background:rgb(252, 250, 241)">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="shop-breadcrumb">
          <div class="breadcrumb-main">
            <h4 class="text-capitalize breadcrumb-title">Menu</h4>
            <div class="breadcrumb-action justify-content-center flex-wrap">

              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Dashboard</a></li>
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
    <div class="container-fluid">
      <div class="card-header my-md-4 text-center py-md-3 rounded-pill" style="background:rgb(237, 201, 136)">
        <h3 class="text-white ">
          <?php echo $_category; ?>
        </h3>
      </div>
      <div class="card product-item p-9" style="background:rgb(248,239,212)">

        <div class="row col-lg-12 justify-content-evenly">
          <?php
          $sql_data = "SELECT * FROM inventory i LEFT JOIN product_img pi ON pi.product_id = i.product_id WHERE product_category = '$category_id' AND i.state = 0";
          $result_data = $conn->query($sql_data);

          if ($result_data->num_rows > 0) {

            while ($row = $result_data->fetch_assoc()) {
              $item_id = $row['product_id'];
              $sql = "SELECT 
                    *, 
                    COUNT(*) AS review_count, 
                    AVG(ratings) AS average_rating 
                FROM rating 
                WHERE item_id = '$item_id'";
              $result_review = $conn->query($sql);
              if ($result_review->num_rows > 0) {
                while ($row_review = $result_review->fetch_assoc()) {
                  $rating = $row_review['average_rating'];
                  $rating_count = $row_review['review_count'];
                }
              } else {
                $rating = "No Rating Yet";
                $rating_count = "0";
              }

              echo '<div class="card col-sm-3 mx-sm-1 my-sm-2 shadow-md ">
                            <div class="card-header px-0">
                              <h2>' . $row['product_name'] . '</h2>
                              <span class="bg-warning px-sm-1 py-sm-1 rounded-end text-white">₱';

              $sql_price_promo = "SELECT * FROM promo WHERE item_id = '$item_id' AND promo_status = 'active'";
              $result_price = $conn->query($sql_price_promo);
              if ($result_price->num_rows > 0) {
                while ($row_price = $result_price->fetch_assoc()) {
                  echo number_format($row_price['item_current_price'], 2);
                }
              } else {
                echo number_format($row['product_price'], 2);
              }

              echo '</span>
                            </div>

                        <div class="card-body p-1">';

              echo '<div class="ratio ratio-4x3 rounded-1">';
              echo '<img src="../uploads/' . $row['img_name'] . '" class="card-img-top img-fluid rounded">';
              echo '</div>';

              echo '<div class=""> 
                            <p>' . $row['product_description'] . '</p>';
              echo '<span>' . number_format($rating, 1) . '</span><br>';

              echo '<div class="stars-rating d-flex align-items-center flex-wrap">';
              $numStars = intval($rating);
              $decimal = $rating - $numStars;

              for ($i = 0; $i < 5; $i++) {
                if ($i < $numStars) {
                  echo '<span class="star-icon las la-star active"></span>';
                } else {
                  if ($decimal > 0) {
                    echo '<span class="star-icon las la-star-half-alt active"></span>';
                    $decimal = 0;
                  } else {
                    echo '<span class="star-icon las la-star"></span>';
                  }
                }
              }

              echo '</div>';
              echo '</div>
                          </div>
                        <div class="card-footer bg-gray rounded row justify-content-center gap-2 ">
                           <a href="ordernow.php?product_id=' . $row['product_id'] . '&category_id=' . $row['product_category'] . '" class="btn btn-warning"><i class="uil uil-eye"></i> View Item</a>
                           <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal'.$row['product_id'].'"><i class="uil uil-shopping-basket"></i> Add to Cart</a>
                      
                          </div>
                        </div>';

$product_id = $row['product_id'];
$product_name = $row['product_name'];

$sql_up_price = "SELECT * FROM promo WHERE item_id = '$product_id' AND promo_status = 'active'";
$res_up_price = $conn->query($sql_up_price);
$final_price = 0;
if ($res_up_price->num_rows > 0) {
    $row_promo = $res_up_price->fetch_assoc();
    $final_price = $row_promo['item_current_price'];
} else {
    $final_price = number_format($row['product_price']);
}
                       echo '
<div class="modal fade" id="editModal' . $product_id . '" tabindex="-1" aria-labelledby="editModal' . $product_id . 'Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="background:rgb(255, 249, 208)">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="new-member-modal">

            <input type="hidden" name="item_id" value="' . $product_id . '">

            <div class="card">
              <div class="card-header">Order</div>
              <div class="card-body">
                <table class="w-100">
                  <thead>
                    <tr>
                      <td class="title">Item</td>
                      <td class="text-center title">Qty</td>
                      <td class="title text-end">Price</td>
                    </tr>
                  </thead>
                  <tbody class="text-dark">
                    <tr>
                    <td>
  <input type="text" name="order_name" class="form-control border-0 bg-white" value="' . $product_name . '" readonly>
</td>
<td>
  <input type="number" class="form-control border-0 bg-white" value="1" name="order_qty" min="1" max="99" onchange="calculateTotal(this)" required>
</td>
<td class="text-end">
  <input type="text" class="form-control text-end border-0 bg-white" value="' . $final_price . '" name="order_price" readonly>
</td>

                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="form-group d-flex justify-content-between align-items-center mt-md-4 px-sm-4">
              <label for="total" class="mr-4 fs-5">Total</label>
              <input type="number" class="form-control text-end w-25" id="total_price" name="total" readonly>
            </div>

            <div class="card-footer d-flex justify-content-evenly align-items-center" style="background:rgb(255, 249, 208)">
              <div class="button-group w-50">
                <button type="submit" name="addtoorder" class="btn btn-squared text-capitalize w-100 text-white" style="background:rgb(19, 39, 67)">Add to Order</button>
              </div>
            </div>

          </div>
        </div>
      </form>

    </div>
  </div>
</div>



   ';
            }
          } else {
            echo '<div class="d-flex flex-column justify-content-center align-items-center my-;lg-4">
                            <img src="./img/Empty-pana.png" class="w-50">
                           
                      </div>';
          }

          ?>
          <!--  -->

          <!--  -->
        </div>
      </div>
    </div>
  </div>



  <!-- modal add -->
  <div class="modal fade" id="editModal12" tabindex="-1" aria-labelledby="editModal12Label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModal12Label">Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>


        <form method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="new-member-modal">

              <input type="hidden" name="item_id" value="<?php #echo $item_id; 
                                                          ?>">
              <input type="hidden" name="id" value="<?php #echo $id; 
                                                    ?>">

              <div class="card ">
                <div class="card-header">Order</div>
                <div class="card-body">
                  <table class="w-100">
                    <thead>
                      <tr>
                        <td class="title">Item</td>
                        <td class="text-center title">Qty</td>
                        <td class="title">Price</td>
                      </tr>
                    </thead>
                    <tbody class="text-dark">
                      <tr>
                        <td><input type="text" name="order_name" class="form-control border-0 bg-white" value="<?php #echo $item_name 
                                                                                                                ?>" readonly></td>
                        <td>
                          <input type="number" id="quantity" class="form-control" value="<?php #echo $item_qty 
                                                                                          ?>" name="order_qty" required>
                        </td>
                        <td class="text-end">
                          <input type="text" class="form-control text-end border-0 bg-white" value="<?php #echo number_format($item_price) 
                                                                                                    ?>" id="item_price" name="order_price" readonly>
                        </td>

                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="form-group d-flex justify-content-between align-items-center mt-md-4 px-sm-4">
                <label for="total" class="mr-4">Total</label>
                <input type="number" class="form-control text-end w-25" id="total_price" name="total" readonly>
              </div>

              <div class=" card-footer d-flex justify-content-evenly align-items-center">
                <div class="button-group w-50 ">
                  <button type="submit" name="addorder" class="btn btn-lighten btn-squared text-capitalize w-100 ">Add to Order</button>
                </div>

                <div class="button-group w-50">
                  <button type="submit" name="otc" class="btn btn-secondary btn-default w-100 btn-squared text-capitalize">Over the
                    Counter</button>

                </div>
              </div>
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

  <script src="js/plugins.min.js"></script>
  <script src="js/script.min.js"></script>
  <script src="./js/sweetalert2.all.min.js"></script>

<script>
function calculateTotal(qtyInput) {
    const row = qtyInput.closest('tr');
    const priceInput = row.querySelector('input[name="order_price"]');
    const modal = qtyInput.closest('.modal');
    const totalField = modal.querySelector('input[name="total"]');

    const qty = parseFloat(qtyInput.value);
    const price = parseFloat(priceInput.value.replace(/,/g, ''));

    if (!isNaN(qty) && !isNaN(price)) {
        totalField.value = (qty * price).toFixed(2);
    } else {
        totalField.value = '';
    }
}

// When modal is shown, calculate total for the default qty
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('shown.bs.modal', function () {
        const qtyInput = modal.querySelector('input[name="order_qty"]');
        if (qtyInput) {
            calculateTotal(qtyInput);
        }
    });
});
</script>

  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init();


    
  </script>


</script>
  </body>

  </html>