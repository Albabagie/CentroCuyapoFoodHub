<?php
include('sidebar.php');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$rootUrl = $protocol . $host;


if (isset($_POST['remove_item'])) {
  $cart_id = $_POST['cart_id'];

  $sql = "UPDATE cart_item SET item_void = 1 WHERE cart_id = ?";
  $result = $conn->prepare($sql);
  $result->bind_param("i", $cart_id);
  if ($result->execute()) {
    $sql_update = "UPDATE cart SET cart_status = 1 WHERE cart_id = ?";
    $result_update = $conn->prepare($sql_update);
    $result_update->bind_param("i", $cart_id);
    if ($result_update->execute()) {
      echo '<script>
      document.addEventListener("DOMContentLoaded", function() {
          Swal.fire({
              title: "item removed",
              text: "list of order updated.",
              icon: "info"
          });
      });
  </script>';
    }

    $result_update->close();
  }
}

if (isset($_POST['placeorder'])) {
  $order_total = $_POST['total'];
  $total_name = 'total';
  $total_id = 1;

  $method = $_POST['payment_method'];
  $status = '';
  // if ($method == 'online') {
  $status = 0;
  // } else {
  //   $status = 0;
  // }





  $order_numbers = rand(111, 999);
  $sql_check_number = "SELECT COUNT(order_number) as numbers FROM orders WHERE order_number = ?";
  $stmt_check = $conn->prepare($sql_check_number);
  $stmt_check->bind_param("s", $order_numbers);
  $stmt_check->execute();
  $stmt_check->bind_result($existing_number);
  $stmt_check->fetch();
  $stmt_check->close();


  if ($existing_number > 0) {
    $update_number = "UPDATE orders SET order_number = 0 WHERE order_number = '$existing_number'";
    $update_res = $conn->query($update_number);
    $order_numbers = rand(111, 999); // new number generated
  }
  $sql_order = "INSERT INTO orders (customer_id, order_date, order_number, mode_of_payment, status) VALUES (?, ?, ?, ?, ?)";
  $stmt_order = $conn->prepare($sql_order);
  $stmt_order->bind_param("isssi", $id, $sqldate, $order_numbers, $method, $status);

  if ($stmt_order->execute()) {
    $order_id = $stmt_order->insert_id;

    foreach ($_POST['item_id'] as $key => $item_id) {
      $item_qty = $_POST['order_qty'][$key];
      $item_name = $_POST['order_name'][$key];
      $item_price = $_POST['price'][$key];

      $item_count = count($_POST['item_id']);

      $item_id = intval($item_id);
      $item_qty = intval($item_qty);
      $item_name = $conn->real_escape_string($item_name);

      $sql_items = "INSERT INTO order_items (order_id, product_id, item_name, item_price, item_qty) VALUES (?, ?, ?, ?, ?)";
      $stmt_items = $conn->prepare($sql_items);
      $stmt_items->bind_param("iissi", $order_id, $item_id, $item_name, $item_price, $item_qty);

      if (!$stmt_items->execute()) {
        echo 'Order item insertion failed: ' . $conn->error;
        break;
      }
    }
    // $payment_date = date('Y-m-d');
    // $payment_status = 'paid';
    // $change = 0;

    // if (isset($method['online'])) {
    //   $sql = "INSERT INTO payment (order_id, payment_date, payment_method, payment_status, paid_amount) VALUES (?,?,?,?,?)";
    //   $result_order = $conn->prepare($sql);
    //   $result_order->bind_param('issss', $order_id, $payment_date, $method, $payment_status, $order_total);

    //   if ($result_order->execute()) {
    //     $payment_id = $conn->insert_id;
    //     $change_tendered = "INSERT INTO tendered (payment_id,item_count,total,change_amt,tend_amt) VALUES (?,?,?,?,?)";
    //     $tend_res = $conn->prepare($change_tendered);
    //     $tend_res->bind_param('iiiii', $payment_id, $item_count, $order_total, $change, $order_total);
    //     if ($tend_res->execute()) {
    //       echo '<script>
    //                     document.addEventListener("DOMContentLoaded", function() {
    //                         Swal.fire({
    //                             title: "Preparing Order.",
    //                             text: "nice.",
    //                             icon: "success"
    //                         });
    //                     });
    //                     setTimeout(function() {
    //                         window.location.href = "index.php";
    //                     }, 1000);
    //                 </script>';
    //     }
    //   }
    // }
    $sql_total = "INSERT INTO order_total (order_id, order_total, total_name, total_amt) VALUES (?, ?, ?, ?)";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bind_param("issi", $order_id, $total_id, $total_name, $order_total);

    if ($stmt_total->execute()) {
      $update = "UPDATE cart SET cart_status = 1 WHERE customer_id = $id";
      $result_up = $conn->query($update);
      if ($result_up === TRUE) {
        foreach ($_POST['item_id'] as $key => $item_id) {
          $cart_id = $_POST['cart_id_unique'][$key];
          $sql_item_up = "UPDATE cart_item SET ordered_item = 1 WHERE customer_id = '$id' AND cart_id = '$cart_id'";
          $result_up = $conn->query($sql_item_up);
          if ($result_up === TRUE) {

            if ($method == 'cash') {
              echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Notice:",
                    text: "Please proceed to the cashier to pay and confirm your orde. Thank you!" ,
                    icon: "info"
                });
            });     
        </script>';
            }
          echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        title: "Order Sent. Your Order Number: ' . $order_numbers . '",
        text: "Please proceed to the cashier to confirm your payment.!",
        icon: "success",
        showCancelButton: true,
        confirmButtonText: "Go to Order History",
        cancelButtonText: "Go to Menu"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "orderhistory.php";
        } else {
            window.location.href = "index.php";
        }
    });

    // Optional: fallback redirect if no interaction within 10 seconds
    setTimeout(function() {
        window.location.href = "index.php";
    }, 10000);
});
</script>';

          } else {
            echo 'transaction failed' . $conn->error;
          }
        }
      }
    } else {
      echo 'Order total insertion failed: ' . $conn->error;
    }
  } else {
    echo 'Order insertion failed: ' . $conn->error;
  }
}




?>
<style>
  @media (max-width: 575.98px) {
    thead {
      display: none;
    }
  }

  @media (min-width: 576px) {
    thead {
      display: table-header-group;
    }
  }
</style>

<div class="contents vh-100" style="background:rgb(252, 250, 241)">

  <div class="container-fluid ">
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
      <div class="row justify-content-center my-md-4">
        <div class="col-lg-10 col-md-8 col-sm-6 justify-content-center">
          <form method="POST">
            <div class="card">
              <div class="card-header px-md-4 text-dark text-capitalize  text-white table-responsive">
                <table class="table table-bordered table-sm align-middle text-nowrap">
                  <thead>
                    <tr>
                      <th>Order Name</th>
                      <th class="text-center">Quantity</th>
                      <th class="text-center">Item Price</th>
                      <th class="text-center">Per Item Total</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <!-- <tbody>
                    <?php
                    $sql = "SELECT c.*, ci.cart_id AS u_cart_id, ci.name, ci.price, ci.qty
                      FROM cart c 
                      LEFT JOIN cart_item ci ON ci.product_id = c.product_id 
                      WHERE c.cart_status = 0 AND ci.item_void = 0 AND c.customer_id = '$id' AND ci.ordered_item = 0 
                      GROUP BY c.customer_id, ci.product_id";
                    $result = $conn->query($sql);
                    $total = 0;

                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        $perItemTotal = $row["price"] * $row["qty"];
                        $total += $perItemTotal;

                        echo '<tr  id="cart-item-' . $row['cart_id'] . '">';
                        echo '<input type="hidden" value="' . $row['cart_id'] . '" name="cart_id_unique[]">';
                        echo '<input type="hidden" value="' . $row['product_id'] . '" name="item_id[]">';
                        echo '<td><input value="' . $row['name'] . '" name="order_name[]" class=" form-control text-truncate" style="max-width: 150px;" readonly></td>';
                        echo '<td>
  <input 
    id="qty_' . $row['cart_id'] . '" 
    value="' . $row['qty'] . '" 
    type="number" 
    class="form-control quantity_input text-center" 
    name="order_qty[]" 
    min="1" max="10" 
    data-price="' . $row['price'] . '" 
    data-id="' . $row['cart_id'] . '"
  >
</td>';
                        echo '<td><input value="' . $row['price'] . '" type="number" class="form-control border-0 text-center" name="price[]" readonly></td>';
                        echo '<td>
  <input 
    id="per_item_' . $row['cart_id'] . '" 
    class="form-control text-center" 
    readonly
  >
</td>';
                        echo '<td>';
                        echo '<a href="javascript:void(0);"
                              type="button"
                              class="btn btn-danger btn-xs remove-item-btn"
                              data-cart-id="' . $row['cart_id'] . '"
                            >
                              <i class="uil uil-trash"></i>
                            </a>';
                        echo '</td>';
                        echo '</tr>';
                      }
                    } else {
                      echo '<tr><td colspan="5" class="text-center">Your cart is empty.</td></tr>';
                    }
                    ?>
                  </tbody> -->
                  <tbody>
                    <?php
                    $sql = "SELECT c.*, ci.cart_id AS u_cart_id, ci.name, ci.price, ci.qty
  FROM cart c 
  LEFT JOIN cart_item ci ON ci.product_id = c.product_id 
  WHERE c.cart_status = 0 AND ci.item_void = 0 AND c.customer_id = '$id' AND ci.ordered_item = 0 
  GROUP BY c.customer_id, ci.product_id";
                    $result = $conn->query($sql);
                    $total = 0;

                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        $perItemTotal = $row["price"] * $row["qty"];
                        $total += $perItemTotal;
                        $cartId = $row['cart_id'];
                        $productId = $row['product_id'];
                        $name = htmlspecialchars($row['name'], ENT_QUOTES);
                        $price = $row['price'];
                        $qty = $row['qty'];

                        echo '<tr class="d-block d-sm-table-row" id="cart-item-' . $cartId . '">';

                        // Order name full row on mobile
                        echo '<td colspan="5" class="fw-bold text-primary d-block d-sm-none">' . $name . '</td>';
                        echo '</tr>';

                        // Main table row
                        echo '<tr class="d-block d-sm-table-row">';

                        // Hidden inputs
                        echo '<input type="hidden" value="' . $cartId . '" name="cart_id_unique[]">';
                        echo '<input type="hidden" value="' . $productId . '" name="item_id[]">';

                        // Order name (desktop only)
                        echo '<td class="d-none d-sm-table-cell">
            <input value="' . $name . '" name="order_name[]" class="form-control text-truncate" style="max-width: 150px;" readonly>
          </td>';

                        // Quantity
                        echo '<td class="d-flex justify-content-between align-items-center d-sm-table-cell">
            <span class="d-sm-none text-muted">Qty:</span>
            <input 
              id="qty_' . $cartId . '" 
              value="' . $qty . '" 
              type="number" 
              class="form-control quantity_input text-center ms-auto" 
              name="order_qty[]" 
              min="1" max="10" 
              data-price="' . $price . '" 
              data-id="' . $cartId . '"
            >
          </td>';

                        // Item price
                        echo '<td class="d-flex justify-content-between align-items-center d-sm-table-cell text-center">
            <span class="d-sm-none text-muted">Price:</span>
            <input value="' . $price . '" type="number" class="form-control border-0 text-end ms-auto" name="price[]" readonly>
          </td>';

                        // Per item total
                        echo '<td class="d-flex justify-content-between align-items-center d-sm-table-cell text-center">
            <span class="d-sm-none text-muted">Total:</span>
            <input 
              id="per_item_' . $cartId . '" 
              class="form-control text-end ms-auto" 
              readonly
            >
          </td>';

                        // Action
                        echo '<td class="d-flex justify-content-between align-items-center d-sm-table-cell text-end">
            <span class="d-sm-none text-muted">Action:</span>
            <a href="javascript:void(0);" 
               class="btn btn-danger btn-xs ms-auto remove-item-btn" 
               data-cart-id="' . $cartId . '">
              <i class="uil uil-trash"></i>
            </a>
          </td>';

                        echo '</tr>';
                      }
                    } else {
                      echo '<tr><td colspan="5" class="text-center">Your cart is empty.</td></tr>';
                    }
                    ?>
                  </tbody>

                </table>
              </div>

              <div class="card-footer rounded-bottom">
                <div class="d-flex justify-content-evenly">
                  <div>
                    <h5>Grand Total:</h5>
                  </div>
                  <div>
                    <input id="total_price" value="<?php echo ($total > 0) ? $total : 0; ?>" class="form-control border-0 text-center" name="total" readonly>
                  </div>
                </div>

                <?php if ($total == 0): ?>
                  <div class="text-center my-3">
                    <button type="submit" class="btn btn-warning" disabled>Place Order</button>
                  </div>
                <?php else: ?>
                  <div class="text-center my-3">
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#paymentModal">
                      Payment Option
                    </button>
                  </div>

                  <!-- Modal inside the same form -->
                  <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="paymentModalLabel">Select Payment Method</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="onlinePayment" value="online" required>
                            <label class="form-check-label" for="onlinePayment">GCash Payment</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cashPayment" value="cash" required>
                            <label class="form-check-label" for="cashPayment">Cash Payment</label>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                          <button type="submit" class="btn btn-primary" name="placeorder">Place Order</button>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="onlinePaymentModal" tabindex="-1" aria-labelledby="onlinePaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="onlinePaymentModalLabel">GCash Payment Instructions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>After scanning the GCash QR code, please screenshot the payment receipt. Then click 'Place Order' and present the screenshot along with your order number to the cashier Thank you!</p>
        <!-- You can add your QR code, UPI ID, etc. here -->

        <?php
        $qr = "SELECT * FROM wallet WHERE active = 1";
        $result_qr = $conn->query($qr);

        $wallet_qr_src = "";

        if ($result_qr && $result_qr->num_rows > 0) {
          $row = $result_qr->fetch_assoc();
          $wallet_qr_src = $row['wallet_qr'];
        }
        ?>
        <img src=" <?php echo  $rootUrl . "/uploads/" .  $wallet_qr_src ?: 'default-qr.png'; ?>" alt="QR Code" class="img-fluid">

        <p class="mt-2"></p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="submitMainForm()">Place Order</button>
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="cashPaymentModal" tabindex="-1" aria-labelledby="cashPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cashPaymentModalLabel">Cash Payment Instructions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Please proceed to the cashier to pay and confirm your order. Thank you!</p>

        <p class="mt-2"></p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="submitMainForm()">Place Order</button>
      </div>

    </div>
  </div>
</div>

</main>


<script>
  function updateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.per_item').forEach(input => {
      total += parseFloat(input.value) || 0;
    });

    const totalField = document.getElementById('total_price');
    totalField.value = total.toFixed(2);

    // Optionally: enable/disable payment button
    const paymentBtn = document.querySelector('[data-bs-target="#paymentModal"]');
    if (paymentBtn) {
      paymentBtn.disabled = (total === 0);
    }

    // Optionally: show/hide empty message
    // if (total === 0) {
    //   const tbody = document.querySelector('tbody');
    //   tbody.innerHTML = '<tr><td colspan="5" class="text-center">Your cart is empty.</td></tr>';
    // }
  }

  document.addEventListener('click', function(event) {
    if (event.target.closest('.remove-item-btn')) {
      const btn = event.target.closest('.remove-item-btn');
      const cartId = btn.getAttribute('data-cart-id');

      // Find the cart item element
      const itemElement = document.getElementById('cart-item-' + cartId);

      if (itemElement) {
        // Remove the item element from the DOM
        itemElement.remove();
        fetch('./action', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              cart_id: cartId
            }) // <-- send JSON object

          })
          .then(response => response.json()) // Parse JSON response
          .then(data => { // <-- Add your code here
            if (data.success) {
              itemElement.remove();
              updateGrandTotal();
              // remove from DOM
              Swal.fire({
                title: 'Item removed',
                text: data.message,
                icon: 'info'
              });
           window.location.reload();

            } else {
              alert('Failed: ' + data.message);
              btn.disabled = false;
            }
          })
          .catch(error => {
            console.error('Error removing item:', cartId);
            alert('An error occurred while removing the item.');
            btn.disabled = false;
          });

      }
    }
  });

  function submitMainForm() {
    const form = document.querySelector('form[method="POST"]');
    if (form) {
      // Optional: add a hidden input to detect online payment from second modal
      const input = document.createElement("input");
      input.type = "hidden";
      input.name = "placeorder";
      input.value = "1";
      form.appendChild(input);

      form.submit();
    }
  }
</script>

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

<script src="./js/sweetalert2.all.min.js"></script>
<script src="js/plugins.min.js"></script>
<script src="js/script.min.js"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>




<script>
  document.addEventListener('DOMContentLoaded', function() {
    const onlinePaymentRadio = document.getElementById('onlinePayment');

    onlinePaymentRadio.addEventListener('change', function() {
      if (this.checked) {
        // First close the current modal
        const currentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
        if (currentModal) {
          currentModal.hide();
        }

        // Then show the online payment modal
        const onlineModal = new bootstrap.Modal(document.getElementById('onlinePaymentModal'));
        onlineModal.show();
      }
    });
  });
</script>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    const caashPaymentRadio = document.getElementById('cashPayment');

    caashPaymentRadio.addEventListener('change', function() {
      if (this.checked) {
        // First close the current modal
        const currentCashModal = bootstrap.Modal.getInstance(document.getElementById('cashPaymentModal'));
        if (currentCashModal) {
          currentCashModal.hide();
        }

        // Then show the online payment modal
        const cashModal = new bootstrap.Modal(document.getElementById('cashPaymentModal'));
        cashModal.show();
      }
    });
  });
</script>

<script>
  const quantityInputs = document.querySelectorAll('.quantity_input');

  function updateTotal() {
    let grandTotal = 0;

    quantityInputs.forEach(function(input) {
      const cartId = input.getAttribute('data-id'); // safer
      const price = parseFloat(input.getAttribute('data-price')) || 0;
      const qty = parseFloat(input.value) || 0;

      const itemTotal = price * qty;

      const perItemInput = document.getElementById('per_item_' + cartId);
      if (perItemInput) {
        perItemInput.value = itemTotal.toFixed(2);
      }

      grandTotal += itemTotal;
    });

    const totalPriceInput = document.getElementById('total_price');
    if (totalPriceInput) {
      totalPriceInput.value = grandTotal.toFixed(2);
    }

    console.log('Updated total:', grandTotal);
  }

  quantityInputs.forEach(function(input) {
    input.addEventListener('input', updateTotal);
  });

  updateTotal();
</script>



<script>
  const quantityInputss = document.querySelectorAll('.quantity_input');
  const totalElement = document.getElementById('total_price');

  quantityInputs.forEach(input => {
    input.addEventListener('change', () => {
      calculateTotal();
    });
  });

  function calculateTotal() {
    let total = 0;
    quantityInputs.forEach(input => {
      const quantity = parseInt(input.value);
      const price = parseFloat(input.dataset.price);
      total += quantity * price;
    });
    totalElement.value = total.toFixed(2);
  }

  calculateTotal();
</script>


<script>
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
        this.jobTitles = ["Active", "Pending", "Rejected"];
        this.jobTitleDefault = "All";
        this.$jobTitle = null;
      },
      $create: function() {
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
</script>



</body>


</html>