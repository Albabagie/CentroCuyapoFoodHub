<?php
include('../connection.php');

// Utility function to limit text by word count


// === Over Orders ===
// <div class="p-2 w-25 text-center"><h2>#</h2></div>
// <div class="p-2 w-25 text-center"><h2>Name</h2></div>
//  <div class='w-25 text-center'><p>$count</p></div>
//     <div class='w-25 text-center'><p>" . $row['over_name'] . "</p></div>

echo '<div class="card over_order" style="display: none;">';
echo '
  <div class="card-header px-4 d-flex justify-content-between">

    <div class="p-2 w-25 text-center"><h2>Order No.</h2></div>
    <div class="p-2 w-25 text-center"><h2>Total</h2></div>
    <div class="p-2 w-25 text-center"><h2>Action</h2></div>
  </div>
  <div class="card-body" style="background:rgb(248,239,212)">';

$order_query = "
  SELECT oi.over_name, o.over_id, o.over_number,o.over_date, COALESCE(SUM(ot.over_tamt), 0) AS over_tamt
  FROM over_orders o
  LEFT JOIN over_items oi ON oi.over_id = o.over_id 
  LEFT JOIN over_total ot ON ot.over_id = o.over_id
  WHERE o.over_status = 0 AND DATE(o.over_date) = '$current_date'
  GROUP BY o.over_id, o.over_number
";

$result = $conn->query($order_query);
$count = 1;

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $class = ($count % 2 == 0) ? 'even' : 'odd';

    echo "<div class='card-body border rounded my-2 $class'>
      <div class='d-flex px-4 justify-content-between align-items-center'>
     
        <div class='w-25 text-center'><p>" . $row['over_number'] . "</p></div>
        <div class='w-25 text-center'><p>" . $row['over_tamt'] . "</p></div>
        <div class='w-25 text-end'>
          <a href='viewover.php?over_id=" . $row['over_id'] . "' class='btn btn-success btn-square'>View <i class='uil uil-arrow-right'></i></a>
        </div>
      </div>
    </div>";
    $count++;
  }
} else {
  echo '<div class="text-center"><span>No Over Orders Yet.</span></div>';
}

echo '</div>'; // close card-body
echo '</div>'; // close card over_order

// === Customer Orders ===
// <div class="p-2 w-25 text-center"><h2>#</h2></div>
// <div class="p-2 w-25 text-center"><h2>Name</h2></div>
//  <div class='w-25 text-center'><p>$count</p></div>
//         <div class='w-25 text-center'><p>" . $row['item_name'] . "</p></div>
echo '<div class="card customer_order">';
echo '
  <div class="card-header px-4 d-flex justify-content-between">

    <div class="p-2 w-25 text-center"><h2>Order No.</h2></div>
    <div class="p-2 w-25 text-center"><h2>Total</h2></div>
    <div class="p-2 w-25 text-center"><h2>Action</h2></div>
  </div>
  <div class="card-body" style="background:rgb(248,239,212)">';

$order_query = "
  SELECT o.order_id, o.order_date, MAX(oi.item_name) AS item_name, o.order_number, ot.total_amt
  FROM orders o
  LEFT JOIN order_items oi ON oi.order_id = o.order_id
  LEFT JOIN order_total ot ON ot.order_id = o.order_id
  WHERE o.status = 0  AND DATE(o.order_date) = '$current_date'
  GROUP BY o.order_id, o.order_number
";

$result = $conn->query($order_query);
$count = 1;

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $class = ($count % 2 == 0) ? 'even' : 'odd';

    echo "<div class='card-body border rounded my-2 $class'>
      <div class='d-flex px-4 justify-content-between align-items-center'>
       
        <div class='w-25 text-center'><p>" . $row['order_number'] . "</p></div>
        <div class='w-25 text-center'><p>" . number_format($row['total_amt'], 0) . "</p></div>
        <div class='w-25 text-end'>
          <a href='vieworder.php?order_id=" . $row['order_id'] . "' class='btn text-white btn-square' style='background:rgb(19, 39, 67)'>View <i class='uil uil-arrow-right'></i></a>
        </div>
      </div>
    </div>";
    $count++;
  }
} else {
  echo '<div class="text-center"><span>No Customer Orders Yet.</span></div>';
}

echo '</div>'; // close card-body
echo '</div>'; // close card customer_order
