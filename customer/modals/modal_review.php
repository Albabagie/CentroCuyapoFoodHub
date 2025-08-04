<?php
$order_items_result = $conn->query("SELECT *, (SELECT SUM(item_price) FROM order_items WHERE order_id = '$order_id') AS total
  FROM orders o 
  LEFT JOIN order_items oi ON oi.order_id = o.order_id 
  WHERE o.order_id = '$order_id'");

echo '<div class="modal fade" id="view_order' . $order_number . '" tabindex="-1" aria-labelledby="view_order' . $order_number . 'Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">We appreciate your feedback!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="text-center">
            <h3>Order Receipt</h3>
            <p>Food Hub | Centro Cuyapo<br>' . date('M - d - Y', strtotime($order_date)) . '<br>
              <strong>Order # ' . $order_number . '</strong>
            </p>
          </div>
          <div class="card-body">';

$counter = 1;
while ($item_row = $order_items_result->fetch_assoc()) {
  echo '
    <div class="card my-2 p-2">
      <div class="d-flex justify-content-between">
        <span>' . $item_row['item_name'] . '</span>
        <span>' . $item_row['item_qty'] . '</span>
        <span>' . $item_row['item_price'] . '</span>
      </div>
      <span>How would you rate the value of the food?</span>
      <div class="rating">';
  for ($i = 5; $i >= 1; $i--) {
    $star_id = 'star_' . $order_number . '_' . $counter . '_' . $i;
    echo '<input type="radio" id="' . $star_id . '" name="rating_' . $counter . '" value="' . $i . '" required>
          <label for="' . $star_id . '"></label>';
  }
  echo '</div>
    <input type="hidden" name="customer_id_' . $counter . '" value="' . $id . '">
    <input type="hidden" name="item_id_' . $counter . '" value="' . $item_row['product_id'] . '">
    <input type="text" class="form-control" name="feedback_' . $counter . '" placeholder="Feedback">
    <input type="hidden" name="order_number_' . $counter . '" value="' . $order_number . '">
    <input type="hidden" name="order_id_' . $counter . '" value="' . $order_id . '">
  </div>';
  $counter++;
}

echo '
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-gray" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning text-white" name="submit_ratings" ' . (($status != 1 && $status != 2 && $status != 3) ? 'disabled' : '') . '>
            <i class="uil uil-star"></i> Give Ratings
          </button>
        </div>
      </form>
    </div>
  </div>
</div>';
?>
