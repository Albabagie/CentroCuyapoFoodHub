<?php
include ('sidebar.php');
$item_id = $_GET['product_id'];

$item = "SELECT * 
FROM inventory
WHERE product_id = '$item_id'";
$result = $conn->query($item);

if ($result->num_rows . 0) {
  while ($row = $result->fetch_assoc()) {
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
    $item_name = $row["product_name"];
    $item_price = $row["product_price"];
    $item_desc = $row["product_description"];
    $item_qty = 1;
  }
}


// 
if (isset($_POST["addtoorder"])) {
  $item_id = $_POST["item_id"];
  $item_name = $_POST["order_name"];
  $item_qty = $_POST["order_qty"];
  $item_desc = $_POST["item_desc"];
  $item_price = $_POST["order_price"];

  $item = $item_name . "-" . strtolower($item_desc);

  $check_sql = "SELECT * FROM cart_item ci LEFT JOIN cart c ON c.product_id = ci.product_id WHERE name = ? AND c.customer_id = ? AND ci.item_void = 0 AND ci.ordered_item = 0 AND c.cart_status = 0";
  $check_stmt = $conn->prepare($check_sql);
  $check_stmt->bind_param("si", $item, $id);
  $check_stmt->execute();
  $check_result = $check_stmt->get_result();
  // resolve cart
  if ($check_result->num_rows > 0) {
    $existing_item = $check_result->fetch_assoc();
    $new_qty = $existing_item['qty'] + $item_qty;
    $update_sql = "UPDATE cart_item SET qty = ? WHERE item_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $new_qty, $existing_item['item_id']);

    if ($update_stmt->execute()) {

      echo '<script>
                  document.addEventListener("DOMContentLoaded", function() {
                      Swal.fire({
                          title: "Item quantity updated.",
                          text: "Nice!",
                          icon: "success"
                      });
                      setTimeout(function() {
                          window.location.href = "orders.php";
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
      $result2->bind_param("ssiiii", $item, $item_qty, $item_price, $id, $item_id, $cart_id);
    }
    if ($result2->execute()) {

      echo '<script>
                  document.addEventListener("DOMContentLoaded", function() {
                      Swal.fire({
                          title: "Item added.",
                          text: "Nice!",
                          icon: "success"
                      });
                      setTimeout(function() {
                          window.location.href = "orders.php";
                      }, 2000);
                  });
              </script>';
    } else {
      echo "Error adding item to cart item table: " . $conn->error;
    }
  }
}



?>



<div class="contents">
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
      <div class="card product-details h-100 border-0">
        <div class="product-item p-sm-50 p-20">
          <div class="row">
            <div class="col-lg-4">
              <div class="product-item__image">
                <div class="wrap-gallery-article carousel slide carousel-fade" id="carouselExampleCaptions"
                  data-bs-ride="carousel">
                  <div>
                    <?php
                    $imageQuery = "SELECT img_name FROM product_img WHERE product_id = ?";
                    $imageStmt = $conn->prepare($imageQuery);
                    $imageStmt->bind_param("i", $item_id);
                    $imageStmt->execute();
                    $imageResult = $imageStmt->get_result();

                    $imageUrls = array();

                    while ($row = $imageResult->fetch_assoc()) {
                      $imageUrls[] = $row['img_name'];
                    }

                    $imageStmt->close();
                    ?>

                    <div class="carousel-inner">
                      <?php foreach ($imageUrls as $index => $imageUrl): ?>
                        <div class="carousel-item<?php echo $index === 0 ? ' active' : ''; ?>">
                          <img class="img-fluid d-flex bg-opacity-primary" src="../uploads/<?php echo $imageUrl; ?>"
                            alt="Card image cap" title="">
                        </div>
                      <?php endforeach; ?>
                    </div>

                  </div>
                  <div class="overflow-hidden">
                    <?php
                    // Assuming $last_id contains the listing ID
                    $imageQuery = "SELECT img_name FROM product_img WHERE product_id = ?";
                    $imageStmt = $conn->prepare($imageQuery);
                    $imageStmt->bind_param("i", $item_id);
                    $imageStmt->execute();
                    $imageResult = $imageStmt->get_result();

                    $imageUrls = array();

                    while ($row = $imageResult->fetch_assoc()) {
                      $imageUrls[] = $row['img_name'];
                    }




                    ?>


                  </div>
                </div>
              </div>
            </div>
            <div class=" col-lg-4">
              <div class=" b-normal-b mb-25 pb-sm-35 pb-15 mt-lg-0 mt-15">
                <div class="product-item__body">
                  <div class="product-item__title">
                    <a href="#">
                      <h1 class="card-title">
                        <?php echo $item_name ?>
                      </h1>
                    </a>
                  </div>
                  <div class="product-item__content text-capitalize">
                    <div class="product-item__content text-capitalize">
                      <div class="product-item__content text-capitalize">
                        <span><?php echo number_format($rating, 1); ?></span>
                        <div class="stars-rating d-flex align-items-center flex-wrap">
                          <?php
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
                          ?>
                          <span class="stars-rating__point">
                            <?php #echo $rating == intval($rating) ? number_format($rating, 0) : number_format($rating, 1); ?>
                          </span>
                          <span class="stars-rating__review">
                            <span>
                              <?php echo $rating_count ?>
                            </span> Reviews
                          </span>
                        </div>
                      </div>

                    </div>


                    <span class="product-desc-price">
                      Price: ₱
                      <?php echo number_format($item_price, 2); ?>
                    </span>
                    <div class="d-flex align-items-center mb-2">

                    </div>
                    <p class=" product-deatils-pera">
                      <?php echo $item_desc; ?>
                    </p>
                    <div class="product-details__availability">
                      <div class="title d-flex flex-column align-items-start">

                      </div>


                    </div>


                  </div>

                </div>
              </div>

            </div>
            <hr>
            <div class="col-lg-12 w-100 d-flex justify-content-center my-lg-2">



              <button class="btn btn-secondary btn-default btn-squared border-0 me-10 my-sm-0 my-2 w-50"
                data-bs-toggle="modal" data-bs-target="#editModal<?php echo $item_id ?>">Order</button>

              <!-- MODAL -->




              <div class="modal fade" id="editModal<?php echo $item_id; ?>" tabindex="-1"
                aria-labelledby="editModal<?php echo $item_id ?>Label" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="editModalLabel">Payment</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>


                    <form method="POST" enctype="multipart/form-data">
                      <div class="modal-body">
                        <div class="new-member-modal">

                          <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">

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
                                    <td><input type="text" name="order_name" class="form-control border-0 bg-white"
                                        value="<?php echo $item_name ?>" readonly></td>
                                    <td>
                                      <input type="number" id="quantity" class="form-control"
                                        value="<?php echo $item_qty ?>" name="order_qty" min="1" max="99" required>
                                    </td>
                                    <td class="text-end">
                                      <input type="text" class="form-control text-end border-0 bg-white"
                                        value="<?php echo number_format($item_price) ?>" id="item_price"
                                        name="order_price" readonly>
                                    </td>
                                    <input type="hidden" value="<?php echo $item_desc; ?>" name="item_desc">

                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="form-group d-flex justify-content-between align-items-center mt-md-4 px-sm-4">
                            <label for="total" class="mr-4">Total</label>
                            <input type="number" class="form-control text-end w-25" id="total_price" name="total"
                              readonly>
                          </div>

                          <div class=" card-footer d-flex justify-content-evenly align-items-center">
                            <div class="button-group w-50 ">
                              <button type="submit" name="addtoorder"
                                class="btn btn-lighten btn-squared text-capitalize w-100 ">Add to Order</button>
                            </div>


                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!--  -->
            </div>


          </div>

        </div>
        <hr>
        <div style="text-align: center; margin-bottom: 5px;height:50px">
          <span style="background-color: #ffffff; padding: 0 10px;">
            <h3 style="display: inline;">Feedback And Rating </h3>
          </span>
        </div>

        <div class="row">
          <?php
          $sql = "SELECT * FROM rating
JOIN customer ON rating.customer_id = customer.customer_id where rating.item_id = '$item_id'";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
              ?>
              <div class="col-md-4 col-md-4 mb-25">
                <div class="card">
                  <div class="user-group px-30 pt-30 pb-25 radius-xl">
                    <div class="border-bottom">
                      <div class="media user-group-media d-flex justify-content-between">
                        <div class="media-body d-flex align-items-center">
                          <img class="me-20 wh-70 rounded-circle bg-opacity-primary" src="img/user.png" alt="author">
                          <div>
                            <h6 class="mt-0  fw-500"><?php echo $row['name'] ?></h6>
                          </div>
                        </div>
                        <div class="mt-n15"></div>
                      </div>
                      <p class="mt-15"><?php echo $row['review'] ?></p>
                    </div>
                    <div class="stars-rating align-items-center">
                      <?php
                      $rating = $row['ratings'];
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
                      ?>
                      <span class="stars-rating__point">
                        <?php echo $rating == intval($rating) ? number_format($rating, 0) : number_format($rating, 1); ?>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <?php
            }
          } else {
            echo " <div class='w-100 text-center my-5'>No Reviews Yet</div>";
          }
          ?>


        </div>

      </div>

    </div>

  </div>
</div>
<footer class="footer-wrapper">
  <div class="footer-wrapper__inside">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="footer-copyright">


            </p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="footer-menu text-end">
            <ul>
              <li>
                <a href="#">About</a>
              </li>
              <li>
                <a href="#">Team</a>
              </li>
              <li>
                <a href="#">Contact</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
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


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
  integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>




<?php


?>

<script>

</script>
<script>
  const quantityInput = document.getElementById('quantity');
  const itemPriceInput = document.getElementById('item_price');
  const totalPriceInput = document.getElementById('total_price');

  function calculateTotal() {
    const quantity = parseInt(quantityInput.value);
    const price = parseFloat(itemPriceInput.value.replace(/[^0-9.-]+/g, '')); // Remove non-numeric characters
    let total = 0; // Default total to zero
    if (!isNaN(quantity) && !isNaN(price)) {
      total = quantity * price;
    }
    totalPriceInput.value = Number(total.toFixed(0));
  }

  // Add event listener to quantity input to recalculate total when quantity changes
  quantityInput.addEventListener('input', calculateTotal);

  // Calculate total initially
  calculateTotal();
</script>







</body>

</html>