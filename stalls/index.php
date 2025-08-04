<?php
include('sidebar.php');



$orders = $conn->query("SELECT COUNT(*) as order_count FROM orders WHERE status = 0  AND DATE(order_date) = '$current_date'");
$list_order = $orders->fetch_assoc()['order_count'];

$over_app = $conn->query("SELECT COUNT(*) AS over_ord FROM over_orders WHERE over_status = 0 AND DATE(over_date) = '$current_date'");
$over_ord = $over_app->fetch_assoc()['over_ord'];

$app_orders = $conn->query("SELECT COUNT(*) AS app_ord FROM orders WHERE status = 0 AND DATE(order_date) = '$current_date'");
$app_count = $app_orders->fetch_assoc()['app_ord'];


$over_count = $conn->query("SELECT COUNT(*) as over_count FROM over_orders WHERE over_status = 0  AND DATE(over_date) = '$current_date'");
$over_res = $over_count->fetch_assoc()['over_count'];

$order_total = $conn->query("SELECT customer_id, COUNT(*) as total_order FROM orders WHERE status = 0  AND DATE(order_date) = '$current_date'");
$order_resl = $order_total->fetch_assoc()['total_order'];

$order_res = $order_resl + $over_res;



if (isset($_POST['updateOrders'])) {
  // When the attendant clicks OK, mark only the items for the current stall
  $order_id  = (int) $_POST['order_id'];
  $stall_id  = (int) $_POST['stall_id'];  // The current stall/category ID
  $table     = $_POST['table'];
  $id_column = $_POST['id_column']; // Not used in the update but still passed in

  /*
   * Update logic:
   * We support two scenarios for marking items as served:
   *   - For customer orders (order_items) we continue to update all items
   *     belonging to the order and the current stall/category via the
   *     product_category filter (same logic as before).
   *   - For over-the-counter items (over_items) we allow either order-level
   *     updates (when id_column is over_id) or row-level updates (when
   *     id_column is overt_item_id). Row-level updates are necessary
   *     because over_items does not have a stall_id column and sometimes
   *     each item needs to be served individually.
   */
  if ($table === 'order_items') {
    // Customer orders: update all items in the order for this stall
    $sql  = "UPDATE order_items
             SET stall_done = 1
             WHERE order_id = ?
               AND product_id IN (SELECT product_id FROM inventory WHERE product_category = ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
      http_response_code(500);
      echo "Failed to prepare statement.";
    }
    $stmt->bind_param('ii', $order_id, $stall_id);
  } else {
    // OTC items: determine whether to update by overt_item_id or over_id
    if ($id_column === 'overt_item_id') {
      // Row-level update: mark a single over_items row as served
      $sql  = "UPDATE over_items SET stall_done = 1 WHERE overt_item_id = ?";
      $stmt = $conn->prepare($sql);
      if (!$stmt) {
        http_response_code(500);
        echo "Failed to prepare statement.";
      }
      $stmt->bind_param('i', $order_id);
    } else {
      // Order-level update: mark all rows in the over_items table for this over order as served.
      // We intentionally do not join to inventory here because over_items does not have a
      // stall_id column and the inventory mapping may vary by system.  This will remove
      // the order from all stalls once served.
      $sql  = "UPDATE over_items SET stall_done = 1 WHERE over_id = ?";
      $stmt = $conn->prepare($sql);
      if (!$stmt) {
        http_response_code(500);
        echo "Failed to prepare statement.";
      }
      $stmt->bind_param('i', $order_id);
    }
  }

  if ($stmt->execute()) {
    // echo "success";
  echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: " Order Complete.",
                        text: "Deliver it to the Pickup Station with the order number",
                        icon: "success"
                    });
                });
                setTimeout(function() {
                    window.location.reload = "index.php";
                }, 3000);
                </script>';

  } else {
    http_response_code(500);
    echo "Failed to update."; 
  }
}





?>

<div class="contents" style="background:rgb(252,250,241)">
  <div class="crm demo6 mb-25">
    <div class="container-fluid">
      <div class="row ">
        <div class="col-lg-12">
          <div class="breadcrumb-main">
            <div class="breadcrumb-action justify-content-center flex-wrap">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Stall Order</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>

        <!-- <div class="col-xxl-6 col-sm-4 mb-25">
          <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between" style="background:rgb(47,179,68); box-shadow: rgba(17, 17, 26, 0.1) 0px 8px 24px, rgba(17, 17, 26, 0.1) 0px 16px 56px, rgba(17, 17, 26, 0.1) 0px 24px 80px;">
            <div class="overview-content w-100" style="background:rgb(47,179,68)">
              <div class=" ap-po-details-content d-flex flex-wrap justify-content-between">
                <div class="ap-po-details__titlebar">
                  <?php
                  if ($order_res != 0) {
                    echo "<h1 class='text-white'>" . $order_res . "</h1>";
                  } else {
                    echo "<h1 class='text-white'>0</h1>";
                  }

                  ?>
                  <h5 class='text-white'>Total Order</h5>
                </div>
                <div class="ap-po-details__icon-area">
                  <div class="svg-icon order-bg-opacity-primary color-info">
                    <i class="uil uil-shopping-bag"></i>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div> -->


        <!-- <div class="col-xxl-6 col-sm-4 mb-25">
          <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between" style="background:rgb(247,103,7);box-shadow: rgba(17, 17, 26, 0.1) 0px 8px 24px, rgba(17, 17, 26, 0.1) 0px 16px 56px, rgba(17, 17, 26, 0.1) 0px 24px 80px;">
            <div class="overview-content w-100" style="background:rgb(247,103,7)">
              <div class=" ap-po-details-content d-flex flex-wrap justify-content-between">
                <div class="ap-po-details__titlebar">
                  <h1 class="text-white">
                    <?php echo $list_order; ?>
                  </h1>
                  <h5 class="text-white">App Order Pending</h5>
                </div>
                <div class="ap-po-details__icon-area">
                  <div class="svg-icon order-bg-opacity-primary color-warnings">
                    <i class="uil uil-users-alt"></i>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div> -->


        <!-- <div class="col-xxl-6 col-sm-4 mb-25">
          <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between" style="background:rgb(117, 49, 136); box-shadow: rgba(17, 17, 26, 0.1) 0px 8px 24px, rgba(17, 17, 26, 0.1) 0px 16px 56px, rgba(17, 17, 26, 0.1) 0px 24px 80px;">
            <div class="overview-content w-100" style="background:rgb(117, 49, 136)">
              <div class=" ap-po-details-content d-flex flex-wrap justify-content-between">
                <div class="ap-po-details__titlebar">
                  <h1 class=" text-white">
                    <?php echo $over_ord; ?>
                  </h1>
                  <h5 class=" text-white">Counter Order Pending</h5>
                </div>
                <div class="ap-po-details__icon-area">
                  <div class="svg-icon order-bg-opacity-purple color-danger">
                    <i class="uil uil-user"></i>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div> -->

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
                    <i class="btn uil uil-book fw-500 border border-light rounded order-btn active" data-target="customer_order">
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
        <!-- <div class="card over_order" style="display: none;">
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


          <div class="card-body" style="background:rgb(248,239,212)">

            <?php
            function limit_text($text, $limit)
            {
              if (str_word_count($text, 0) > $limit) {
                $words = str_word_count($text, 2);
                $pos = array_keys($words);
                $text = substr($text, 0, $pos[$limit]) . '...';
              }
              return $text;
            }

            $order_list = "SELECT * FROM over_orders o 
            LEFT JOIN over_items oi ON oi.over_id = o.over_id 
            LEFT JOIN over_total ot ON ot.over_id = oi.over_id 
            WHERE o.over_status = 0 GROUP BY o.over_id";
            $orderResult = $conn->query($order_list);
            $count = 1;
            if ($orderResult->num_rows > 0) {
              while ($row = $orderResult->fetch_assoc()) {
                $class = '';
                if (($count % 2 == 0)) {
                  $class = 'even';
                } else {
                  $class = 'odd';
                }
                // ($count % 2 == 0) ? 'even' : 'odd';
                echo "<div class='card-body border rounded my-2 $class'>
                                    <ul class='d-flex px-4 justify-content-between align-items-center'>
                                        <div class='text-capitalize w-25 text-center mx-2'>
                                            <p class='w-50'>$count</p>
                                        </div>
                                        <div class='text-capitalize w-25 text-center mx-2'>
                                            <p class='w-50'>" . limit_text($row['over_name'], 5) . "</p>
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
        </div> -->

        <!-- customer orders -->
        <!-- <div class="card customer_order">
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
          <div class="card-body" style="background:rgb(248,239,212)">
            <?php
            $order_list = "SELECT * FROM orders o 
            LEFT JOIN order_items oi ON oi.order_id = o.order_id 
            LEFT JOIN order_total ot ON ot.order_id = oi.order_id 
            WHERE o.status = 0 GROUP BY o.order_id";
            $orderResult = $conn->query($order_list);
            $count = 1; // Initialize count variable
            if ($orderResult->num_rows > 0) {
              while ($row = $orderResult->fetch_assoc()) {
                $class = '';

                if (($count % 2 == 0)) {
                  $class = 'even';
                } else {
                  $class = 'odd';
                }
                // ($count % 2 == 0) ? 'even' : 'odd'; // Check if it's an even or odd row
                echo "<div class='card-body border rounded my-2 $class'>
                                    <ul class='d-flex px-4 justify-content-between align-items-center'>
                                        <div class='text-capitalize w-25 text-center mx-2'>
                                            <p class='w-50'>$count</p>
                                        </div>
                                        <div class='text-capitalize w-25 text-center mx-2'>
                                            <p class='w-50'>" . limit_text($row['item_name'], 5) . "</p>
                                        </div>
                                        <div class='text-capitalize w-25 text-center mx-2'>
                                            <p>" . $row['order_number'] . "</p>
                                        </div>
                                        <div class='text-capitalize w-25 text-center mx-2'>
                                            <p>" . $row['total_amt'] . "</p>
                                        </div>
                                        <div class='text-capitalize w-25 mx-2 d-flex justify-content-end'>
                                            <a href='vieworder.php?order_id=" . $row['order_id'] . "' class='btn btn-square $class-button text-end text-white' style='background:rgb(19, 39, 67)'>View<i class='uil uil-arrow-right'></i></a>
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
        </div> -->
        <div id="orders-container">
          <?php
          // Load the order listing for the current stall. We explicitly include
          // the local order_fetch (1).php file (which contains updated logic
          // for over‑the‑counter and app orders) rather than relying on a
          // possibly missing or outdated order_fetch.php.
          include('order_fetch (1).php');
          ?>
        </div>

        <!--  -->
      </div>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.all.min.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgYKHZB_QKKLWfIRaYPCadza3nhTAbv7c"></script>
<script src="js/plugins.min.js"></script>
<script src="js/script.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".order-form").forEach(function(form) {
      form.addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        const orderId = form.dataset.orderId;
        const submitButton = form.querySelector("button");

        // Optional: Disable the button while processing
        submitButton.disabled = true;
        submitButton.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`;

        // Submit to the current page explicitly using the window location.  Using an empty
        // string as the URL can fail when the file name contains spaces or special
        // characters (e.g. "index (1).php"), resulting in a failed fetch and no
        // pop‑up.  This ensures the POST goes to the correct PHP file.
        fetch(window.location.pathname, {
            method: "POST",
            body: formData,
          })
          .then(response => {
            if (!response.ok) throw new Error("Network response failed");
            return response.text();
          })
          .then(() => {
            Swal.fire({
              title: "Order Completed",
              text: "All items in this order marked as done.",
              icon: "success"
            });

            // Remove the card
            const card = document.getElementById("order-card-" + orderId);
            if (card) card.remove();
          })
          .catch(() => {
            Swal.fire({
              title: "Error",
              text: "Could not update the order. Try again.",
              icon: "error"
            });
          })
          .finally(() => {
            // Re-enable button
            submitButton.disabled = false;
            submitButton.innerHTML = `OK <i class="uil uil-arrow-right"></i>`;
          });
      });
    });
  });
</script>


<script>
  $(document).on("click", ".update-item", function() {
    const itemId = $(this).data("id");
    const tableName = $(this).data("table");
    const idColumn = $(this).data("id-column");

    // Debugging: Log the data being sent
    console.log("Sending:", {
      id: itemId,
      table: tableName,
      id_column: idColumn
    });

    $.ajax({
      url: "update_stall_item.php",
      type: "POST",
      contentType: "application/x-www-form-urlencoded", // Or application/json if you want JSON payload
      dataType: "json", // expecting JSON response
      data: {
        id: itemId,
        table: tableName,
        id_column: idColumn
      },
      success: function(response) {
        console.log("✅ Success Response:", response);

        if (response.success) {
          console.log("🟢 Message:", response.message);
          alert("Updated: " + response.message);
        } else {
          console.warn("⚠️ Update not applied:", response.message);
          alert("Warning: " + response.message);
        }
      },
      error: function(xhr, status, error) {
        console.error("❌ AJAX Error:");
        console.error("Status:", status);
        console.error("Error:", error);
        console.error("Response Text:", xhr.responseText);
        alert("Error: " + xhr.responseText);
      }
    });

  });
</script>



<script>
  function reloadOrders() {
    console.log('[Reload Orders] Fetching new data...');

    fetch('order_fetch.php')
      .then(response => {
        if (!response.ok) {
          console.error('[Reload Orders] Network error:', response.status);
          throw new Error('Network response was not OK');
        }
        return response.text();
      })
      .then(html => {
        const container = document.getElementById('orders-container');

        if (!container) {
          console.warn('[Reload Orders] orders-container not found in DOM');
          return;
        }

        const oldContent = container.innerHTML;
        container.innerHTML = html;

        if (oldContent === html) {
          console.log('[Reload Orders] No changes in order data.');
        } else {
          console.log('[Reload Orders] Orders updated.');
        }
      })
      .catch(err => {
        console.error('[Reload Orders] Error loading orders:', err);
      });
  }

  // Initial load
  reloadOrders();

  // Repeat every 30 seconds
  setInterval(reloadOrders, 10000);
</script>


<script>
  document.addEventListener("DOMContentLoaded", function() {
    var orderBtns = document.querySelectorAll('.order-btn');

    orderBtns.forEach(function(btn) {
      btn.addEventListener('click', function() {
        orderBtns.forEach(function(btn) {
          btn.classList.remove('active');
        });

        this.classList.add('active');

        var targetCard = this.getAttribute('data-target');

        var cards = document.querySelectorAll('.card');
        cards.forEach(function(card) {
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