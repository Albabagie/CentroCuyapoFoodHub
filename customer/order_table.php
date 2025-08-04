<?php
include('session.php');
// session_start();

// $id = $_SESSION['customer_id'] ?? null;
// // $current_date = date("Y-m-d");

// // // Debug echo and exit to test
// if (!$id) {
//   echo '<tr><td colspan="7">[DEBUG] No user ID</td></tr>';
//   exit;
// }
// echo '<tr><td colspan="7">[DEBUG] PHP reach here with ID='.$$id.'</td></tr>';

$order_items = $conn->query("SELECT o.order_id as order_m, o.status, o.order_date, o.customer_id, o.order_number, p.*, ot.*, oi.* 
FROM orders o 
LEFT JOIN order_total ot ON ot.order_id = o.order_id 
LEFT JOIN order_items oi ON oi.order_id = o.order_id 
LEFT JOIN payment p ON p.order_id = o.order_id
WHERE o.customer_id = $id AND DATE(o.order_date) = '$current_date'
GROUP BY o.order_id
ORDER BY o.order_date DESC");

if ($order_items->num_rows > 0) {
  while ($row = $order_items->fetch_assoc()) {
    $order_id = $row['order_id'];
    $order_number = $row['order_number'];
    $payment = $row['payment_status'];
    $status = $row['status'];
    $order_date = $row['order_date'];

    echo '<tr>
      <td class="text-center"><span>' . $order_number . '</span></td>
      <td><span>' . $row['total_amt'] . '</span></td>
      <td><span>' . date('Y-m-d', strtotime($order_date)) . '</span></td>
      <td>';

    switch ($status) {
      case 3: echo '<span class="text-white p-sm-1 rounded text-center" style="background:rgb(215, 56, 94)">Out</span>'; break;
      case 2: echo '<span class="text-white p-sm-1 rounded text-center" style="background:rgb(5, 146, 18)">Ready</span>'; break;
      case 0: echo '<span class="text-white p-sm-1 rounded text-center" style="background:rgb(97, 94, 252)">Waiting for Approval</span>'; break;
      case 1: echo '<span class="text-white p-sm-1 rounded text-center" style="background:rgb(64, 132, 235)">Preparing</span>'; break;
      case 4: echo '<span class="text-white p-sm-1 rounded text-center" style="background:rgb(153, 151, 151)">Cancelled</span>'; break;
    }

    echo '</td>
      <td>' . ($payment == 'paid' ? '<div class="userDatatable-content">Success</div>' : '<div class="userDatatable-content">Not paid</div>') . '</td>
      <td>';

    $exist_rating = $conn->query("SELECT * FROM rating WHERE order_number = '$order_number'");
    $row_exist = $exist_rating->fetch_assoc();

   if ($row_exist && $row_exist['order_number'] == $order_number) {
    if (($status == 1 || $status == 3) && $payment == 'paid') {
        echo '<button class="btn btn-light btn-square border border-lighten" data-bs-toggle="modal" data-bs-target="#view_order' . $order_number . '" disabled>Thank you! <i class="uil uil-heart text-danger"></i></button>';
    }
} elseif ($status == 3 && $payment == 'paid') {
    echo '<button class="btn btn-warning btn-square text-white" data-bs-toggle="modal" data-bs-target="#view_order' . $order_number . '"><i class="uil uil-feedback"></i>Write Review</button>';
} elseif ($status == 0 && $payment == 'paid') {
    echo '<button class="btn btn-gray btn-square text-white" data-bs-toggle="modal" data-bs-target="#order_item' . $order_number . '"><i class="uil uil-feedback">View Order</i></button>';
} else {
    echo '<button class="btn btn-gray btn-square text-white" data-bs-toggle="modal" data-bs-target="#order_item' . $order_number . '"><i class="uil uil-feedback">View Order </i></button>';
}


    echo '</td>
      <td>
        <form method="POST">
          <input type="hidden" value="' . $order_id . '" name="order_id">
          <button type="submit" name="cancelOrder" class="btn btn-danger btn-square text-white"' . ($status != 0 ? ' disabled' : '') . '>
            <i class="uil uil-times"></i> Cancel
          </button>
        </form>
      </td>
    </tr>';

     include 'modals/modal_review.php';
  include 'modals/modal_order.php';
  include 'modals/modal_payment.php';
  }
} else {
  echo '<tr><td colspan="7" class="text-center">No orders found.</td></tr>';
}
?>
