<?php
include ('sidebar.php');
$orders = $conn->query("SELECT COUNT(*) as order_count FROM orders WHERE status = 0 ");
$list_order = $orders->fetch_assoc()['order_count'];

$over_app = $conn->query("SELECT COUNT(*) AS over_ord FROM over_orders WHERE over_status = 0");
$over_ord = $over_app->fetch_assoc()['over_ord'];

$app_orders = $conn->query("SELECT COUNT(*) AS app_ord FROM orders WHERE status = 0");
$app_count = $app_orders->fetch_assoc()['app_ord'];


$over_count = $conn->query("SELECT COUNT(*) as over_count FROM over_orders");
$over_res = $over_count->fetch_assoc()['over_count'];

$order_total = $conn->query("SELECT customer_id, COUNT(*) as total_order FROM orders");
$order_resl = $order_total->fetch_assoc()['total_order'];

$order_res = $order_resl + $over_res;

?>

<div class="contents">
  <div class="crm demo6 mb-25">
    <div class="container-fluid">
      <div class="row ">
        <div class="col-lg-12">
          <div class="breadcrumb-main">
            <div class="breadcrumb-action justify-content-center flex-wrap">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Order</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
        <div class="col-xxl-6 col-sm-4 mb-25">
          <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
            <div class="overview-content w-100">
              <div class=" ap-po-details-content d-flex flex-wrap justify-content-between">
                <div class="ap-po-details__titlebar">
                  <?php
                  if ($order_res != 0) {
                    echo "<h1>" . $order_res . "</h1>";
                  } else {
                    echo "<h1>0</h1>";
                  }

                  ?>
                  <h5>Total Order</h5>
                </div>
                <div class="ap-po-details__icon-area">
                  <div class="svg-icon order-bg-opacity-primary color-primary">
                    <i class="uil uil-shopping-bag"></i>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>


        <div class="col-xxl-6 col-sm-4 mb-25">
          <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
            <div class="overview-content w-100">
              <div class=" ap-po-details-content d-flex flex-wrap justify-content-between">
                <div class="ap-po-details__titlebar">
                  <h1>
                    <?php echo $list_order; ?>
                  </h1>
                  <h5>Total Pending</h5>
                </div>
                <div class="ap-po-details__icon-area">
                  <div class="svg-icon order-bg-opacity-warning color-warning">
                    <i class="uil uil-users-alt"></i>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>


        <div class="col-xxl-6 col-sm-4 mb-25">
          <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
            <div class="overview-content w-100">
              <div class=" ap-po-details-content d-flex flex-wrap justify-content-between">
                <div class="ap-po-details__titlebar">
                  <h1>
                    <?php echo $over_ord; ?>
                  </h1>
                  <h5>Counter Order Pending</h5>
                </div>
                <div class="ap-po-details__icon-area">
                  <div class="svg-icon order-bg-opacity-warning color-warning">
                    <i class="uil uil-user"></i>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12 ">
            <div class="breadcrumb-main user-member justify-content-sm-between ">
              <div class="d-flex flex-wrap justify-content-between breadcrumb-main__wrapper w-100">
                <div class="d-flex align-items-center user-member__title justify-content-center me-sm-25">
                  <h2 class="text-capitalize fw-500 breadcrumb-title">Orders</h2>
                </div>

                <div class="d-flex">
                  <div class="d-flex align-items-center user-member__title justify-content-center me-sm-25">
                    <i class="btn uil uil-book order-btn border-light rounded mx-sm-2" data-target="over_order">
                      <span class="p-md-2 mx-sm-2"> Over The Counter</span>

                      <?php
                      if ($over_ord != 0) {
                        echo ' <span class="mx-sm-2 mx-md-2 mx-lg-2 bg-primary px-sm-2 rounded text-white">' . $over_ord . ' </span>';
                      } else {
                        echo '';
                      }
                      ?>

                    </i>
                  </div>
                  <div class="d-flex align-items-center user-member__title justify-content-center me-sm-25">
                    <i class="btn uil uil-book fw-500 border border-light rounded order-btn active"
                      data-target="customer_order">
                      <span class="p-md-2 mx-sm-2">App Order</span>
                      <?php if ($app_count != 0) {
                        echo '<span class="mx-sm-2 mx-md-2 mx-lg-2 bg-primary px-sm-2 rounded text-white">' . $app_count . '</span>';

                      } ?>

                    </i>
                  </div>
                </div>

              </div>

              <div class="action-btn">
                <!-- <a href="addlisting.php" class="btn px-15 btn-primary">
                    <i class="las la-plus fs-16"></i>Orders</a> -->
              </div>
            </div>
          </div>
        </div>



        <!-- over orders -->
        <div class="card over_order" style="display: none;">
          <div class="card-header px-4 d-flex justify-content-between">
            <div class="p-2 w-25 text-center">
              <h2>#</h2>
            </div>
            <div class="p-2 w-25 text-center">
              <h2>Name</h2>
            </div>
            <div class="p-2 w-25 text-center">
              <h2>order No.</h2>
            </div>
            <div class="p-2 w-25 text-center">
              <h2>Total</h2>
            </div>
            <div class="p-2 w-25 text-center">
              <h2>Action</span>
            </div>

          </div>


          <div class="card-body">

            <?php
            $order_list = "SELECT * FROM over_orders o 
            LEFT JOIN over_items oi ON oi.over_id = o.over_id 
            LEFT JOIN over_total ot ON ot.over_id = oi.over_id 
            WHERE o.over_status = 0 GROUP BY o.over_id";
            $orderResult = $conn->query($order_list);
            $count = 1;
            if ($orderResult->num_rows > 0) {
              while ($row = $orderResult->fetch_assoc()) {
                $class = ($count % 2 == 0) ? 'even' : 'odd';
                echo "<div class='card-body border rounded my-2 $class'>
                                    <ul class='d-flex px-4 justify-content-between align-items-center'>
                                        <div class='text-capitalize w-25 text-center mx-2'>
                                            <p class='w-50'>$count</p>
                                        </div>
                                        <div class='text-capitalize w-25 text-center mx-2'>
                                            <p class='w-50'>" . $row['over_name'] . "</p>
                                        </div>
                                        <div class='text-capitalize w-25 text-center mx-2'>
                                            <p>" . $row['over_number'] . "</p>
                                        </div>
                                        <div class='text-capitalize w-25 text-center mx-2'>
                                            <p>" . $row['over_tamt'] . "</p>
                                        </div>
                                        <div class='text-capitalize w-25 mx-2 d-flex justify-content-end'>
                                            <a href='viewover.php?over_id=" . $row['over_id'] . "' class='btn btn-success btn-square $class-button text-end'>View<i class='uil uil-arrow-right'></i></a>
                                        </div>
                                    </ul>
                                </div>";
                $count++;
              }
            } else {
              echo '<div class="text-center">';
              echo '<span >No Order Yet.</span>';
              echo '</div>';
            }

            ?>
          </div>
        </div>

        <!-- customer orders -->
        <div class="card customer_order">
          <div class="card-header px-4 d-flex justify-content-between">
            <div class="p-2 w-25 text-center">
              <h2>#</h2>
            </div>
            <div class="p-2 w-25 text-center">
              <h2>Name</h2>
            </div>
            <div class="p-2 w-25 text-center">
              <h2>order No.</h2>
            </div>
            <div class="p-2 w-25 text-center">
              <h2>Total</h2>
            </div>
            <div class="p-2 w-25 text-center">
              <h2>Action</span>
            </div>

          </div>
          <div class="card-body">
            <?php
            $order_list = "SELECT * FROM orders o 
            LEFT JOIN order_items oi ON oi.order_id = o.order_id 
            LEFT JOIN order_total ot ON ot.order_id = oi.order_id 
            WHERE o.status = 0 GROUP BY o.order_id";
            $orderResult = $conn->query($order_list);
            $count = 1; // Initialize count variable
            if ($orderResult->num_rows > 0) {
              while ($row = $orderResult->fetch_assoc()) {
                $class = ($count % 2 == 0) ? 'even' : 'odd'; // Check if it's an even or odd row
                echo "<div class='card-body border rounded my-2 $class'>
                                    <ul class='d-flex px-4 justify-content-between align-items-center'>
                                        <div class='text-capitalize w-25 text-center mx-2'>
                                            <p class='w-50'>$count</p>
                                        </div>
                                        <div class='text-capitalize w-25 text-center mx-2'>
                                            <p class='w-50'>" . $row['item_name'] . "</p>
                                        </div>
                                        <div class='text-capitalize w-25 text-center mx-2'>
                                            <p>" . $row['order_number'] . "</p>
                                        </div>
                                        <div class='text-capitalize w-25 text-center mx-2'>
                                            <p>" . $row['total_amt'] . "</p>
                                        </div>
                                        <div class='text-capitalize w-25 mx-2 d-flex justify-content-end'>
                                            <a href='vieworder.php?order_id=" . $row['order_id'] . "' class='btn btn-success btn-square $class-button text-end'>View<i class='uil uil-arrow-right'></i></a>
                                        </div>
                                    </ul>
                                </div>";
                $count++; // Increment count
              }
            } else {
              echo '<div class="text-center">';
              echo '<span >No Order Yet.</span>';
              echo '</div>';
            }

            ?>
          </div>
        </div>

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
            <p><span>© 2025</span><a href="#">Food Hub</a>
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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgYKHZB_QKKLWfIRaYPCadza3nhTAbv7c"></script>
<script src="js/plugins.min.js"></script>
<script src="js/script.min.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    var orderBtns = document.querySelectorAll('.order-btn');

    orderBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        orderBtns.forEach(function (btn) {
          btn.classList.remove('active');
        });

        this.classList.add('active');

        var targetCard = this.getAttribute('data-target');

        var cards = document.querySelectorAll('.card');
        cards.forEach(function (card) {
          card.style.display = 'none';
        });

        var selectedCard = document.querySelector('.' + targetCard);
        if (selectedCard) {
          selectedCard.style.display = 'block';
        }
      });
    });

    var initialCard = document.querySelector('.customer_order');
    initialCard.style.display = 'block';
  });
</script>
</body>


</html>