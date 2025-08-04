<?php
$items_result = $conn->query("SELECT *, item_price * item_qty AS total FROM order_items WHERE order_id = '$order_id'");
$total_ = 0;
$status_order = $status == 4;

echo '<div class="modal fade" id="order_item' . $order_number . '" tabindex="-1" aria-labelledby="order_item' . $order_number . 'Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="text-center">
            <h3>' . ($status_order ? 'Cancelled' : 'Your') . ' Orders</h3>
            <p>Food Hub | Centro Cuyapo<br>' . date('M - d - Y', strtotime($order_date)) . '<br>
              <strong>Order # ' . $order_number . '</strong>
            </p>
          </div>
          <div class="card-body">';

if ($items_result) {
  while ($row_items = $items_result->fetch_assoc()) {
    $total_ += $row_items['total'];
    echo '<div class="d-flex justify-content-between">
      <span>' . $row_items['item_name'] . '</span>
      <span>' . $row_items['item_qty'] . '</span>
      <span>' . number_format($row_items['item_price']) . '</span>
      <span>' . number_format($row_items['total']) . '</span>
    </div>';

    echo '<input type="hidden" name="items[]" value="' . $row_items['item_name'] . '">';
    echo '<input type="hidden" name="item_qty[]" value="' . $row_items['item_qty'] . '">';
    echo '<input type="hidden" name="price[]" value="' . $row_items['item_price'] . '">';
  }
}

$tbill = $total_ * 100;

echo '<div class="my-4 d-flex justify-content-between">
  <h5>Total:</h5><strong>' . number_format($total_) . '</strong>
</div>
        </div>
        <div class="modal-footer">';

if ($payment == 'paid') {
  echo '<button type="submit" class="btn btn-secondary" disabled>Paid</button>';
} else {
  echo '<input type="hidden" name="order_id" value="' . $order_id . '">
        <input type="hidden" name="bill" value="' . $tbill . '">';
  if ($wallet_qr != '') {
    echo '<button type="button" class="btn btn-primary" onClick="handlePayment(\'' . $order_number . '\')" ' . ($status_order ? 'disabled' : '') . '>Online Payment</button>';
  } else {
    echo '<button type="submit" name="not_available" class="btn btn-primary">Online Payment</button>';
  }

  echo '<button type="submit" name="payment_method_cash" class="btn btn-success" ' . ($status_order ? 'disabled' : '') . '>Cash Payment</button>';
}

echo '</div>
      </form>
    </div>
  </div>
</div>';
?>
