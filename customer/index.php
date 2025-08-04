<?php

include('sidebar.php');


if (isset($_POST['addtogo'])) {
  $item_id = $_POST['item_id'];


  $check_sql = "SELECT * FROM `bag` WHERE `customer_id` = ? AND `item_id` = ?";
  $check_stmt = $conn->prepare($check_sql);
  $check_stmt->bind_param("ii", $id, $item_id);
  $check_stmt->execute();
  $check_result = $check_stmt->get_result();

  if ($check_result->num_rows > 0) {
    echo '<script>alert("already added to bag!")</script>';
  } else {
    $insert_sql = "INSERT INTO `bag`(`customer_id`, `item_id`) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ii", $id, $item_id);
    $insert_stmt->execute();
    echo '<script>window.location.href = "togo.php";</script>';
  }
}

// $sql_promos = "SELECT * FROM promo WHERE promo_status = 'active'";
// $sql_result = $conn->query($sql_promos);

// if($sql_result->num_rows > 0){
//   while($row = $sql_result->fetch_assoc()) {
//     $date_active = $_POST['active_promo'];
//   }
// }
?>

<div class="contents" style="background:rgb(252, 250, 241)">

  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12">
        <div class="shop-breadcrumb">
          <div class="breadcrumb-main">
            <h4 class="text-capitalize breadcrumb-title">Products</h4>
            <div class="breadcrumb-action justify-content-center flex-wrap">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Products</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>

      </div>



    </div>
  </div>


  <div class="products_page product_page--grid mb-30">
    <div class="container-fluid">
      <div class="row justify-content-center">

        <div class="col-lg-10">
          <div class="shop_products_top_filter">
            <div class="project-top-wrapper d-flex flex-wrap align-items-center">
              <div class="project-top-right d-flex flex-wrap align-items-center">
                <div class="project-category flex-wrap d-flex align-items-center">


                </div>
                <div class=" project-icon-selected content-center mt-lg-0 mt-25"></div>
              </div>
            </div>
          </div>

          <div class=" row product-page-list">
            <?php
            $sqls = "SELECT * FROM menu WHERE status = 'available'";
            $result = $conn->query($sqls);

            if ($result->num_rows > 0) {
              // output data of each row
              while ($row = $result->fetch_assoc()) {
                echo '<div class="row col-sm-4 me-sm-1 justify-content-center" ">
                <div class="card position-relative border-1 border-lighten my-sm-2" style="background:rgb(248, 239, 212); box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;">
                    <div class="">
          <img src="../uploads/' . $row["image_stall"] . '" class="card-img-top p-4" alt="...">
            </div>';
        

                echo '<div class="card-body">
                    <h5 class="card-title ">' . $row["category"] . '</h5>
                    <p class="card-text">'.$row['stall_description'].'</p>
                </div>
                <div class="card-footer rounded row justify-content-around" style="">
                  <a  href="viewmenu.php?category_id=' . $row["category_id"] . '"  class="btn me-sm-1 w-50 text-white" type="button" style="background:rgb(255,190,51);">View Menu</a>
                </div>
            </div>
        </div>';
              }
            } else {
              echo '0';
            }

            ?>

          </div>
         
          </main>

          <script src="js/plugins.min.js"></script>
          <script src="js/script.min.js"></script>
         
          </script>
          </body>


          </html>