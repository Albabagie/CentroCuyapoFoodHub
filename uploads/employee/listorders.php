<?php
include ('sidebar.php');


if (isset($_POST['updateOrder'])) {
  // pass orders shzt
  $order_id = $_POST['order_id'];
  $ready_order = $conn->query("UPDATE orders SET status = 2 WHERE order_id = '$order_id'");

  if ($ready_order) {
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: "Preparing Order.",
            text: "nice.",
            icon: "success"
        });
    });
    setTimeout(function() {
        window.reload.href = "index.php";
    }, 1000);
</script>';
  } else {
    echo "Error updating order: " . $conn->error;
  }
}

if (isset($_POST['updateover'])) {
  // pass orders shzt
  $over_id = $_POST['over_id'];
  $ready_order = $conn->query("UPDATE over_orders SET over_status = 2 WHERE over_id = '$over_id'");

  if ($ready_order) {
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: "Preparing Order.",
            text: "nice.",
            icon: "success"
        });
    });
    setTimeout(function() {
        window.reload.href = "index.php";
    }, 1000);
</script>';
  } else {
    echo "Error updating order: " . $conn->error;
  }
}
?>

<div class="contents">
  <div class="container-fluid">
    <div class="social-dash-wrap">
      <div class="row">
        <div class="col-lg-12">
          <div class="breadcrumb-main">
            <h4 class="text-capitalize breadcrumb-title">Orders</h4>
            <div class="breadcrumb-action justify-content-center flex-wrap">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Order List</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="card my-lg-5 ">
            <div class="card-header">
              <h4> Orders</h4>
            </div>
            <div class="card-body d-flex flex-row">

              <?php

              $over_list = $conn->query("SELECT * FROM over_orders o
              LEFT JOIN over_items oi ON oi.over_id = o.over_id 
              LEFT JOIN over_total ot ON oi.over_id = ot.over_id  
              WHERE over_status = 1");


              if ($over_list->num_rows > 0) {
                $over_ord = array();
                //orders -> over_list
                while ($over = $over_list->fetch_assoc()) {
                  $over_status = $over['over_status'];

                  if (!isset($over_ord[$over['over_id']])) {

                    $over_ord[$over['over_id']] = array(
                      'over_id' => $over['over_id'],
                      'over_number' => $over['over_number'],
                      'items' => array()
                    );
                  }

                  $over_ord[$over['over_id']]['items'][] = $over['over_name'] . ' <span class="text-dark fs-5"> x ' . $over['over_qty'] . '</span>';


                }
                foreach ($over_ord as $over_id => $over_data) {
                  // echo json_encode($order_data, JSON_PRETTY_PRINT);
                  echo '<div class="card w-50 mx-sm-2 border-0">';
                  echo '<div class="card-header bg-gray text-white mt-2 ">' . $over_data['over_number'] . '';
                  echo '<div class="badge rounded position-absolute top-0 mb-5 bg-light fs-5 py-1 px-1">Counter Order</div>';
                  if ($over_status == 1) {
                    // di pa tapos dito ka bukas!
                    echo '<form method="POST">';
                    echo '<input type="hidden" value="' . $over_data['over_id'] . '" name="over_id">';
                    echo '<button type="submit" class="rounded-pill bg-warning text-white btn btn-square" name="updateover"><i class="uil uil-arrow-up "></i>Ready</button>';
                    echo '</form>';

                  }
                  echo '</div>';
                  echo '<div class="card-body" style="background-color:#f4f5f7;">';

                  foreach ($over_data['items'] as $item) {

                    echo '<div>' . $item . '</div>';
                  }
                  echo '</div>';
                  echo '</div>';

                }
              } else {
                echo '<div class="text-center w-100 ">
  <span class="">No Orders Yet.</span>
   </div>';
              }


              ?>
              <?php
              $order_list = $conn->query("SELECT *,   oi.item_qty AS items_qty
             FROM orders o
             LEFT JOIN order_items oi ON oi.order_id = o.order_id 
             LEFT JOIN order_total ot ON oi.order_id = ot.order_id  
             WHERE status = 1
             ");

              if ($order_list->num_rows > 0) {
                $orders = array();

                while ($order = $order_list->fetch_assoc()) {
                  $order_status = $order['status'];

                  if (!isset($orders[$order['order_id']])) {

                    $orders[$order['order_id']] = array(
                      'order_id' => $order['order_id'],
                      'order_number' => $order['order_number'],
                      'items' => array()
                    );
                  }

                  $orders[$order['order_id']]['items'][] = $order['item_name'] . ' <span class="text-dark fs-5"> x ' . $order['item_qty'] . '</span>';


                }
                foreach ($orders as $order_id => $order_data) {
                  echo '<div class="card w-50 mx-sm-2 border-0">';
                  echo '<div class="card-header bg-gray text-white">' . $order_data['order_number'] . '';
                  echo '<div class="badge rounded position-absolute top-0 mb-5 bg-light fs-5 py-1 px-1">App Order</div>';
                  if ($order_status == 1) {
                    // di pa tapos dito ka bukas!
                    echo '<form method="POST">';
                    echo '<input type="hidden" value="' . $order_data['order_id'] . '" name="order_id">';
                    echo '<button type="submit" class="rounded-pill bg-warning text-white btn btn-square" name="updateOrder"><i class="uil uil-arrow-up "></i>Ready</button>';
                    echo '</form>';

                  }
                  echo '</div>';
                  echo '<div class="card-body" style="background-color:#f4f5f7;">';

                  foreach ($order_data['items'] as $item) {

                    echo '<div>' . $item . '</div>';
                  }
                  echo '</div>';
                  echo '</div>';

                }
              } else {
                echo '';
              }

              ?>

            </div>
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
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgYKHZB_QKKLWfIRaYPCadza3nhTAbv7c"></script>
  <script src="js/plugins.min.js"></script>
  <script src="js/script.min.js"></script>
  <script src="https://checkout.stripe.com/checkout.js"></script>
  <script>
    var stripe = Stripe('pk_test_51Nzrf4Id5WzwE9nmz6QQ06udHZ3k7wucYVgtgA3mWIkkqChWzAg9HizLVyN3Fuc2c7b4UBjx46kt7tpLBHddjxDf00CmqhIZOu');
    var checkoutButton = document.getElementById('customButton');

    checkoutButton.addEventListener('click', function () {
      stripe.redirectToCheckout({
        items: [{ sku: 'sku_123', quantity: 1 }], // Replace with your own SKU
        successUrl: 'https://your-website.com/success',
        cancelUrl: 'https://your-website.com/cancel',
      });
    });
  </script>
  <script>
    $((function () {
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
        construct: function (t) {
          this._super(t);
          this.jobTitles = ["Active", "Pending", "Rejected"];
          this.jobTitleDefault = "All";
          this.$jobTitle = null;
        },
        $create: function () {
          this._super();
          var t = this,
            s = $("<div/>", {
              class: "form-group dm-select d-flex align-items-center adv-table-searchs__status my-xl-25 my-15 mb-0 me-sm-30 me-0"
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
          $.each(t.jobTitles, (function (e, s) {
            t.$jobTitle.append($("<option/>").text(s));
          }));
        },
        _onJobTitleDropdownChanged: function (t) {
          var e = t.data.self,
            s = $(this).val();
          s !== e.jobTitleDefault ? e.addFilter("status", s, ["status"]) : e.removeFilter("status");
          e.filter();
        },
        draw: function () {
          this._super();
          var e = this.find("status");
          e instanceof FooTable.Filter ? this.$jobTitle.val(e.query.val()) : this.$jobTitle.val(this.jobTitleDefault);
        }
      });
  </script>



  </body>


  </html>