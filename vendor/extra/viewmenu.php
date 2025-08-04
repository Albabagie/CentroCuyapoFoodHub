<?php
include ('sidebar.php');

$category_id = $_GET['category_id'];

$category = $conn->query("SELECT category FROM menu WHERE category_id = '$category_id'");
$_category = $category->fetch_assoc()['category'];



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
      <div class="card-header my-md-4 text-center py-md-3 rounded-pill">
        <h3>
          <?php echo $_category; ?>
        </h3>
      </div>
      <div class="card product-item p-9">

        <div class="row col-lg-12 justify-content-evenly">
          <?php
            $sql_data = "SELECT * FROM inventory i LEFT JOIN product_img pi ON pi.product_id = i.product_id WHERE product_category = '$category_id'";
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

              echo '<div class="card col-sm-3 mx-sm-1 my-sm-2 shadow-md " data-aos="fade-left">
                            <div class="card-header px-0">
                              <h2>' . $row['product_name'] . '</h2>
                              <span class="bg-warning px-sm-1 py-sm-1 rounded-end text-white">₱ ' . number_format($row['product_price'], 2) . '</span>
                            </div>

                        <div class="card-body p-1">
                          <img src="../uploads/' . $row['img_name'] . '" alt="..." class="card-img-top">
                          <div class=""> 
                            <p>' . $row['product_description'] . '</p>';
                            echo '<span>'.number_format($rating,1).'</span><br>';

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
                        <div class="card-footer bg-gray rounded row justify-content-center ">
                           <a href="ordernow.php?product_id=' . $row['product_id'] . '" class="btn btn-warning">View Item</a>
                          </div>
                        </div>';
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


    <!-- modal add -->
    <div class="modal fade" id="editModal12" tabindex="-1"
                aria-labelledby="editModal12Label" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="editModal12Label">Payment</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>


                    <form method="POST" enctype="multipart/form-data">
                      <div class="modal-body">
                        <div class="new-member-modal">

                          <input type="hidden" name="item_id" value="<?php #echo $item_id; ?>">
                          <input type="hidden" name="id" value="<?php #echo $id; ?>">

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
                                        value="<?php #echo $item_name ?>" readonly></td>
                                    <td>
                                      <input type="number" id="quantity" class="form-control"
                                        value="<?php #echo $item_qty ?>" name="order_qty" required>
                                    </td>
                                    <td class="text-end">
                                      <input type="text" class="form-control text-end border-0 bg-white"
                                        value="<?php #echo number_format($item_price) ?>" id="item_price"
                                        name="order_price" readonly>
                                    </td>

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
                              <button type="submit" name="addorder"
                                class="btn btn-lighten btn-squared text-capitalize w-100 ">Add to Order</button>
                            </div>

                            <div class="button-group w-50">
                              <button type="submit" name="otc"
                                class="btn btn-secondary btn-default w-100 btn-squared text-capitalize">Over the
                                Counter</button>

                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
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
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
  </body>

  </html>