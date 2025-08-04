<?php

include('sidebar.php');

require_once('../vendor/autoload.php');

use GuzzleHttp\Client;

if (isset($_POST['pay'])) {

  $client = new Client();
  $order_id = $_POST['order_id'];
  $bill = $_POST['bill'] * 1;
  $order_name = $_POST['items'];
  $order_qtys = $_POST['item_qty'];
  $order_prices = $_POST['price'];

  $currency = 'PHP';
  $description = 'Payment Order';

  
  $success_url = "http://localhost/web_pos_inv-main/customer/orderhistory?order_id=" . $order_id;

  $line_items = [];

  for ($i = 0; $i < count($order_name); $i++) {
    $name = (string) $order_name[$i];
    $qty = (int) $order_qtys[$i];
    $price = (float) $order_prices[$i];

    $bill += $price * $qty;

    $line_items[] = [
      'currency' => $currency,
      'amount' => $price * 100, // Convert to centavos
      'name' => $name,
      'quantity' => $qty
    ];
  }

  try {
    $response = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
      'body' => json_encode([
        'data' => [
          'attributes' => [
            'send_email_receipt' => true,
            'show_description' => true,
            'show_line_items' => true,
            'line_items' => $line_items,
            'payment_method_types' => ['gcash'],
            'reference_number' => $order_id,
            'description' => $description,
            'success_url' => $success_url
          ]
        ]
      ]),
      'headers' => [
        'Content-Type' => 'application/json',
        'accept' => 'application/json',
        'authorization' => 'Basic ' . base64_encode($apiKey),
      ],
    ]);

    $responseData = json_decode($response->getBody(), true);
    $link = $responseData['data']['attributes']['checkout_url'];

    // header("Location: $link");

    echo "<script>
    window.location.href = '" . $link . "';
    </script>";
  } catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
  }
}




if ($stmt = $conn->prepare("SELECT * FROM order_total WHERE order_id = ?")) {
  $stmt->bind_param("i", $order_id);
  $stmt->execute();
  $result_qp = $stmt->get_result();

  if ($row_qp = $result_qp->fetch_assoc()) {
    $order_qp_amt = $row_qp['total_amt'];
  } else {
    $order_qp_amt = 0;
  }

  $stmt->close();
} else {
  echo "Error preparing statement: " . $conn->error;
}

if (isset($_GET['order_id'])) {
  $order_id = $_GET['order_id'];

  if ($stmt = $conn->prepare("SELECT * FROM order_total WHERE order_id = ?")) {
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result_qp = $stmt->get_result();

    if ($row_qp = $result_qp->fetch_assoc()) {
      $order_qp_amt = $row_qp['total_amt'];
    } else {
      $order_qp_amt = 0;
    }

    $stmt->close();
  } else {
    echo "Error preparing statement: " . $conn->error;
  }

  $order_id = $_GET['order_id'];
  $payment_date = date('Y-m-d');
  $payment_menthod = 'Gcash';
  $payment_status = 'paid';
  $paid_amount = $order_qp_amt;

  $stmt = $conn->prepare("INSERT INTO payment (order_id, payment_date, payment_method, payment_status, paid_amount) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("issss", $order_id, $payment_date, $payment_menthod, $payment_status, $paid_amount);

  if ($stmt->execute() === TRUE) {
    echo "New payment record created successfully";
  } else {
    echo "Payment Error: " . $stmt->error;
  }
}

if (isset($_POST['submit_ratings'])) {


  $counter = 1;
  while (isset($_POST['rating_' . $counter])) {
    $customer_id = $_POST['customer_id_' . $counter];
    $item_id = $_POST['item_id_' . $counter];
    $rating = $_POST['rating_' . $counter];

    if (isset($_POST['feedback_' . $counter])) {
      $feedback = $_POST['feedback_' . $counter];
    } else {
      $feedback = NULL;
    }
    $order_number = $_POST['order_number_' . $counter];
    $order_id = $_POST['order_id_' . $counter];

    $sql = "INSERT INTO rating (customer_id, item_id, ratings, review, order_number, order_id) VALUES ('$customer_id','$item_id', '$rating', '$feedback','$order_number', '$order_id')";
    $result_sql = $conn->query($sql);
    if ($result_sql) {
      echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire({
                      title: "Rating Submitted!",
                      text: "Thank you for Feedback, Appreciated.",
                      icon: "success"
                  });
              });
          </script>';
      // exit();
    } else {
      echo '<script>alert("okay mali sya");</script>';
      echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
    }

    $counter++;
  }
}


$wallet = "SELECT * FROM wallet WHERE active = true ORDER BY id ASC";

$wallet_res = $conn->query($wallet);
$wallet_qr = '';
if ($wallet_res->num_rows > 0) {
  while ($r = $wallet_res->fetch_assoc()) {
    $wallet_qr = $r['wallet_qr'];
  }
}

if (isset($_POST['not_available'])) {
  echo '<script>
  document.addEventListener("DOMContentLoaded", function() {
     Swal.fire({
    title: "Online payment is currently unavailable.",
    text: "We appreciate your understanding. Please proceed with cash payment at the cashier.",
    icon: "info"
    });

  });
</script>';
}
if (isset($_POST['payment_method_cash'])) {
  echo '<script>
  document.addEventListener("DOMContentLoaded", function() {
  Swal.fire({
    title: "Kindly head to the Cashier for Payment.",
    text: "Thank you for your cooperation.",
    icon: "success"
});
  });
</script>';

  header('Location: index');
}


if (isset($_POST['cancelOrder'])) {
  // pass orders shzt
  $order_id = $_POST['order_id'];
  $ready_order = $conn->query("UPDATE orders SET status = 4 WHERE order_id = '$order_id'");

  if ($ready_order) {

    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: "Order has been Cancelled.",
            text: "order update.",order_id
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
<div class="contents" style="background:rgb(252, 250, 241)">
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
                  <li class="breadcrumb-item active" aria-current="page">Order List </li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card my-sm-4">
            <div class="card-body rounded" style="background:rgb(255, 249, 208)">
              <div class="userDatatable adv-table-table global-shadow border-light-0 w-100 adv-table">
                <div class="table-responsive">
                  <div class="adv-table-table__header">
                    <h4>My Orders</h4>
                  </div>
                  <div id="filter-form-container"></div>
                  <!-- <table class="table mb-0 table-borderless adv-table1" data-filter-container="#filter-form-container"
                    data-paging-current="1" data-paging-position="right" data-paging-size="5">
                    <thead class="">
                      <tr class="userDatatable-header">



                        <th class="text-center">
                          <span class="userDatatable-title">Order Number</span>
                        </th>
                        <th>
                          <span class="userDatatable-title ">Total Payment</span>
                        </th>
                        <th>
                          <span class="userDatatable-title ">Order Date</span>
                        </th>
                        <th>
                          <span class="userDatatable-title ">Status</span>
                        z
                        </th>
                        <th>
                          <span class="userDatatable-title ">Action</span>
                        </th>
                        <th>
                          <span class="userDatatable-title "></span>
                        </th>
                      </tr>
                    </thead>
                    <tbody id="order-table-body">
                      <?php include 'order_table.php';


                      // ✅ Review Modal
                      include 'modals/modal_review.php';

                      // ✅ Order Details Modal
                      include 'modals/modal_order.php';

                      // ✅ Payment Modal (Optional - shared)
                      include 'modals/modal_payment.php';

                      ?>



                    </tbody>
 adv-table1"
                    data-filter-container="#filter-form-container"
                    data-paging-current="1"
                    data-paging-position="right"
                    data-paging-size="5"
                  </table> -->

                  <table class="table mb-0 table-borderless">
                    <thead>
                      <tr class="userDatatable-header">
                        <th class="text-center"><span class="userDatatable-title">Order Number</span></th>
                        <th><span class="userDatatable-title">Total Payment</span></th>
                        <th><span class="userDatatable-title">Order Date</span></th>
                        <th><span class="userDatatable-title">Status</span></th>
                        <th><span class="userDatatable-title">Payment</span></th>
                        <th><span class="userDatatable-title">Action</span></th>
                        <th><span class="userDatatable-title"></span></th>
                      </tr>
                    </thead>
                    <tbody id="order-table-body">
                      <?php include 'order_table.php'; ?>
                    </tbody>
                  </table>

                  <!-- Modals should be loaded only once -->
                  <?php
                  include 'modals/modal_review.php';
                  include 'modals/modal_order.php';
                  include 'modals/modal_payment.php';
                  ?>

                </div>
              </div>
            </div>
          </div>
        </div>


        <!-- modal start payment -->
        <div class="modal fade" id="payments" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="paymentsLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Payment | Cuyapo Food Hub</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <form method="POST">
                <div class="modal-body text-center">
                  <p class="text-danger">* Please head to the counter after using online payment for verification *</p>';

                  <?php
                  if ($wallet_qr != '') {
                    echo '<img src="../uploads/' . $wallet_qr . '" style="max-width: 100%; max-height: 300px;">';
                  } else {
                    echo '<p>Online Payment is not available right now.</p>';
                  }
                  ?>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>


        <div class="modal fade" id="paymentNoticeModal" tabindex="-1" aria-labelledby="paymentNoticeLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="paymentNoticeLabel">Payment Notice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                Please head to counter after using online payment to be verified. Thank youu
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        <script src="js/plugins.min.js"></script>
        <script src="js/script.min.js"></script>
        <!-- <script>
        function noticePayment() {
          Swal.fire({
            icon: 'info',
            title: 'Online Payment Notice',
            text: 'Please head to the counter after using online payment to be verified. Thank you!',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
          });
        }
      </script> -->
        <script>
          console.log(document.querySelectorAll('.modal'));

          function handlePayment(orderNumber) {
            const modalSelector = '#order_item' + orderNumber;
            console.log(modalSelector);

            // Hide the modal using jQuery  
            $(modalSelector).modal('hide');

            // Wait for the modal to fully hide before showing the alert
            $(modalSelector).one('hidden.bs.modal', function() {
              Swal.fire({
                icon: 'info',
                title: 'Online Payment Notice',
                text: 'Please head to the counter after using online payment to be verified. Thank you!',
                confirmButtonText: 'OK'
              }).then((result) => {
                if (result.isConfirmed) {
                  setTimeout(() => {
                    $('#payments').modal('show');
                  }, 500);

                }
              });
            });
          }
        </script>
        <script>
          function reloadOrderTable() {
            console.log('[OrderTable] Reloading...');

            $('#order-table-body').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');

            $('#order-table-body').load('order_table.php', function(response, status, xhr) {
              if (status === "success") {
                console.log("[OrderTable] Data loaded");

                // Reinitialize FooTable
                $('.adv-table1').data('footable')?.destroy(); // destroy old
                $('.adv-table1').footable(); // re-init
              } else {
                console.error("[OrderTable] Load failed:", status, xhr);
                $('#order-table-body').html('<tr><td colspan="7" class="text-danger text-center">Failed to load data.</td></tr>');
              }
            });
          }

          setInterval(reloadOrderTable, 10000);
        </script>



        <!-- <script>
          $((function() {
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
              construct: function(t) {
                this._super(t);
                this.jobTitles = ["Not Paid", "Success", "Cancelled"];
                this.jobTitleDefault = "All";
                this.$jobTitle = null;
              },
              $create: function() {
                this._super();
                var t = this,
                  s = $("<div />", {
                    class: "form-group dm-select d-flex align-items-center adv-table-searchs__status my-xl-25 my-15 mb-0 me-sm-30 me-0"
                  }).append($("<label />", {
                    class: "d-flex align-items-center mb-sm-0 mb-2",
                    text: "Status"
                  })).prependTo(t.$form);
                t.$jobTitle = $("<select />", {
                  class: "form-control ms-sm-10 ms-0"
                }).on("change", {
                  self: t
                }, t._onJobTitleDropdownChanged).append($("<option/>", {
                  text: t.jobTitleDefault
                })).appendTo(s);
                $.each(t.jobTitles, (function(e, s) {
                  t.$jobTitle.append($("<option/>").text(s));
                }));
              },
              _onJobTitleDropdownChanged: function(t) {
                var e = t.data.self,
                  s = $(this).val();
                s !== e.jobTitleDefault ? e.addFilter("status", s, ["status"]) : e.removeFilter("status");
                e.filter();
              },
              draw: function() {
                this._super();
                var e = this.find("status");
                e instanceof FooTable.Filter ? this.$jobTitle.val(e.query.val()) : this.$jobTitle.val(this.jobTitleDefault);
              }
            });
        </script> -->


        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.all.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </body>


        </html>