<?php
include('session.php');


// echo '<div class="card over_order" style="display: none;">';
// echo '
//   <div class="card-header px-4 d-flex justify-content-between">
//     <div class="p-2 w-25 text-center"><h2>#</h2></div>
//     <div class="p-2 w-25 text-center"><h2>Name</h2></div>
//     <div class="p-2 w-25 text-center"><h2>Order No.</h2></div>
//     <div class="p-2 w-25 text-center"><h2>Quantity</h2></div>
//     <div class="p-2 w-25 text-center"><h2>Action</h2></div>
//   </div>
//   <div class="card-body" style="background:rgb(248,239,212)">';

// $order_query = "

// SELECT 
//   i.product_category,
//   oi.over_name,
//   oi.item_id,
//   oi.over_qty,
//   oi.over_id,
//   o.over_number,
//   oi.over_price,
//   oi.overt_item_id,
//   ot.over_tamt,
//   o.over_date
// FROM 
//   over_items oi
// LEFT JOIN 
//   inventory i ON i.product_id = oi.item_id
// LEFT JOIN 
//   over_total ot ON ot.over_id = oi.over_id
// LEFT JOIN 
//   over_orders o ON o.over_id = oi.over_id
// WHERE 
//   i.product_category = $category
//   AND DATE(o.over_date) = '$current_date'
//   AND oi.stall_done = 0
// GROUP BY 
//   oi.over_id, o.over_number
// ORDER BY 
//   oi.over_id;


// ";

// $result = $conn->query($order_query);
// $count = 1;

// if ($result && $result->num_rows > 0) {
//   while ($row = $result->fetch_assoc()) {
//     $class = ($count % 2 == 0) ? 'even' : 'odd';

//     echo "<div class='card-body border rounded my-2 $class'>
//       <div class='d-flex px-4 justify-content-between align-items-center'>
//         <div class='w-25 text-center'><p>$count</p></div>
//         <div class='w-25 text-center'><p>" . $row['over_name'] . "</p></div>
//         <div class='w-25 text-center'><p>" . $row['over_number'] . "</p></div>
//         <div class='w-25 text-center'><p>" . $row['over_number'] . "</p></div>
//         <div class='w-25 text-center'><p>" . $row['over_qty'] . "</p></div>
//         <div class='w-25 text-end'>
//       <a href='javascript:void(0);'
//    class='btn btn-success btn-square update-item'
//    data-id='" . $row['overt_item_id'] . "'
//    data-table='over_items'
//    data-id-column='overt_item_id'>
//    OK <i class='uil uil-arrow-right'></i>
// </a>


//         </div>
//       </div>
//     </div>";
//     $count++;
//   }

// } else {
//   echo '<div class="text-center"><span>No Over Orders Yet.</span></div>';
// }

// echo '</div>'; 
// echo '</div>'; 


// echo '<div class="card customer_order">';
// echo '
//   <div class="card-header px-4 d-flex justify-content-between">
//     <div class="p-2 w-25 text-center"><h2>#</h2></div>
//     <div class="p-2 w-25 text-center"><h2>Name</h2></div>
//     <div class="p-2 w-25 text-center"><h2>Order No.</h2></div>
//     <div class="p-2 w-25 text-center"><h2>Quantity</h2></div>
//     <div class="p-2 w-25 text-center"><h2>Action</h2></div>
//   </div>
//   <div class="card-body" style="background:rgb(248,239,212)">';



// $order_query = "
// SELECT 
//   o.order_id,
//   o.order_number,
//   o.order_date,
//   ot.total_amt,
//   i.product_category,
//   oi.item_name,
//   oi.item_price,
//   oi.order_item_id,
//   oi.item_qty,
//   oi.stall_done
// FROM 
//   orders o
// JOIN 
//   order_items oi ON oi.order_id = o.order_id
// JOIN 
//   inventory i ON i.product_id = oi.product_id
// LEFT JOIN 
//   order_total ot ON ot.order_id = o.order_id
// WHERE 
//   o.status = 1
//   AND DATE(o.order_date) = '$current_date'
//   AND i.product_category = $category
//   AND oi.stall_done = 0
// ORDER BY 
//   o.order_id, oi.item_name
// ";

// $result = $conn->query($order_query);
// $count = 1;

// if ($result && $result->num_rows > 0) {
//   while ($row = $result->fetch_assoc()) {
//     $class = ($count % 2 == 0) ? 'even' : 'odd';

//     echo "<div class='card-body border rounded my-2 $class'>
//       <div class='d-flex px-4 justify-content-between align-items-center'>
//         <div class='w-25 text-center'><p>$count</p></div>
//         <div class='w-25 text-center'><p>" . $row['item_name'] . "</p></div>
//         <div class='w-25 text-center'><p>" . $row['order_number'] . "</p></div>
//         <div class='w-25 text-center'><p>" . $row['item_qty'] . "x</p></div>
//         <div class='w-25 text-end'>
//       <a href='javascript:void(0);'
//    class='btn btn-success btn-square update-item'
//    data-id='" . $row['order_item_id'] . "'
//    data-table='order_items'
//    data-id-column='order_item_id'>
//    OK <i class='uil uil-arrow-right'></i>
// </a>


//         </div>
//       </div>
//     </div>";
//     $count++;
//   }
// } else {
//   echo '<div class="text-center"><span>No Customer Orders Yet.</span></div>';
// }

// echo '</div>'; 
// echo '</div>';



// === FUNCTION TO RENDER GROUPED CARD ===
/**
 * Render grouped orders for either over-the-counter or customer orders.
 *
 * This function groups rows by the appropriate primary key (order_id or over_id)
 * and prints a card per order. It aligns the order number and item names
 * properly across multiple rows and includes hidden inputs for the stall.
 *
 * @param mysqli_result $result   The query result to render.
 * @param string        $type     Either 'order' for customer orders or 'over' for over‑the‑counter.
 * @param int|null      $stallId  The stall/category ID for the current dashboard.
 */
function renderGroupedOrders($result, $type = 'order', $stallId = null)
{
  // Determine which fields to use based on type
  $idField      = $type === 'over' ? 'over_id'      : 'order_id';
  $numberField  = $type === 'over' ? 'over_number'  : 'order_number';
  $nameField    = $type === 'over' ? 'over_name'    : 'item_name';
  $qtyField     = $type === 'over' ? 'over_qty'     : 'item_qty';

  // Group rows by primary id
  $grouped = [];
  while ($row = $result->fetch_assoc()) {
    $key = $row[$idField];
    if (!isset($grouped[$key])) {
      $grouped[$key] = [
        'id'    => $key,
        'items' => []
      ];
    }
    $grouped[$key]['items'][] = $row;
  }

  $count = 1;
  foreach ($grouped as $orderId => $group) {
    // We will determine whether there are unserved items later when rendering the button.
    echo "<div class='card-body border rounded my-2' id='order-card-$orderId'>";

    // First item row: show order number, item name, qty
    $firstItem = $group['items'][0];
    echo "<div class='d-flex px-4 justify-content-between align-items-center'>
            <div class='w-25 text-center'><p>$count</p></div>
            <div class='w-25 text-center'><p>{$firstItem[$numberField]}</p></div>
            <div class='w-25 text-center'><p>{$firstItem[$nameField]}</p></div>
            <div class='w-25 text-center'><p>{$firstItem[$qtyField]}x</p></div>
            <div class='w-25'></div>
          </div>";

    // Subsequent items: leave first two columns blank
    foreach ($group['items'] as $index => $item) {
      if ($index > 0) {
        echo "<div class='d-flex px-4 justify-content-between align-items-center'>
                <div class='w-25 text-center'></div>
                <div class='w-25 text-center'></div>
                <div class='w-25 text-center'><p>{$item[$nameField]}</p></div>
                <div class='w-25 text-center'><p>{$item[$qtyField]}x</p></div>
                <div class='w-25'></div>
              </div>";
      }
    }

    // Submit form for the entire order. We always show the OK button; the update logic
    // in index (1).php will only affect items belonging to the current stall and that
    // have not already been marked served.
    echo "<div class='d-flex justify-content-end pe-4 pb-2'>";
    echo "<form method='POST' class='order-form' data-order-id='$orderId'>";
    // Include a hidden field to trigger the update in index (1).php. Without
    // this field the updateOrders check in index (1).php is never met when
    // using FormData for AJAX submissions.
    echo "<input type='hidden' name='updateOrders' value='1'>";
    echo "<input type='hidden' name='order_id' value='$orderId'>";
    echo "<input type='hidden' name='table' value='" . ($type === 'over' ? 'over_items' : 'order_items') . "'>";
    echo "<input type='hidden' name='id_column' value='$idField'>";
    echo "<input type='hidden' name='stall_id' value='$stallId'>";
    echo "<button type='submit' class='btn btn-success btn-square' name='updateOrders'>";
    echo "OK <i class='uil uil-arrow-right'></i>";
    echo "</button>";
    echo "</form>";
    echo "</div>";

    echo '</div>'; // end card
    $count++;
  }

  // If no orders were grouped, display a message
  if (empty($grouped)) {
    echo '<div class="text-center"><span>No ' . ucfirst($type) . ' Orders Yet.</span></div>';
  }
}




// === OVER ORDERS CARD ===
echo '<div class="card over_order" style="display: none;">';
echo '
  <div class="card-header px-4 d-flex justify-content-between">
    <div class="p-2 w-25 text-center"><h2>#</h2></div>
    <div class="p-2 w-25 text-center"><h2>Order Number</h2></div>
    <div class="p-2 w-25 text-center"><h2>Order Item</h2></div>
    <div class="p-2 w-25 text-center"><h2>Quantity</h2></div>
    <div class="p-2 w-25 text-center"><h2>Action</h2></div>
  </div>
  <div class="card-body" style="background:rgb(248,239,212)">';

/*
 * Over orders grouped by order.  We fetch over orders and their items
 * without joining the inventory table to avoid schema mismatches.  The
 * results are grouped by over_id and rendered via renderGroupedOrders().
 */
$over_query = "
  SELECT
    o.over_id,
    o.over_number,
    o.over_date,
    oi.over_name,
    oi.over_qty
  FROM over_orders o
  JOIN over_items oi ON oi.over_id = o.over_id
  ORDER BY o.over_date ASC, o.over_number ASC, oi.over_name ASC
";
$over_result = $conn->query($over_query);
if ($over_result && $over_result->num_rows > 0) {
  // Group and render over orders. Pass the current stall ID so the update logic
  // knows which stall to mark done (even though the query itself is not filtered).
  renderGroupedOrders($over_result, 'over', $category);
} else {
  echo '<div class="text-center"><span>No Over Orders Yet.</span></div>';
}
echo '</div>';
echo '</div>';

// === CUSTOMER ORDERS CARD ===
echo '<div class="card customer_order">';
echo '
  <div class="card-header px-4 d-flex justify-content-between">
    <div class="p-2 w-25 text-center"><h2>#</h2></div>
    <div class="p-2 w-25 text-center"><h2>Order Number</h2></div>
    <div class="p-2 w-25 text-center"><h2>Orders</h2></div>
    <div class="p-2 w-25 text-center"><h2>Order Quantity</h2></div>
    <div class="p-2 w-25 text-center"><h2>Action</h2></div>
  </div>
  <div class="card-body" style="background:rgb(248,239,212)">';

$order_query = "
  SELECT 
    o.order_id,
    o.order_number,
    o.order_date,
    ot.total_amt,
    i.product_category,
    oi.item_name,
    oi.item_price,
    oi.order_item_id,
    oi.item_qty,
    oi.stall_done
  FROM orders o
  JOIN order_items oi ON oi.order_id = o.order_id
  JOIN inventory i ON i.product_id = oi.product_id
  LEFT JOIN order_total ot ON ot.order_id = o.order_id
  WHERE o.status = 1
    AND DATE(o.order_date) = '$current_date'
    AND i.product_category = $category
    AND oi.stall_done = 0
  ORDER BY o.order_date ASC, o.order_number ASC, oi.item_name ASC
";

$order_result = $conn->query($order_query);
if ($order_result && $order_result->num_rows > 0) {
  // Pass the stall/category ID for customer orders as well
  renderGroupedOrders($order_result, 'order', $category);
} else {
  echo '<div class="text-center"><span>No Customer Orders Yet.</span></div>';
}
echo '</div>'; // card-body
echo '</div>'; // card
