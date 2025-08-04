<?php 
include 'connection.php';


// $app_order = "SELECT item_price, item_name, product_id, item_qty, SUM(item_qty) as total 
//               FROM order_items
//               GROUP BY item_name";
// $app_order_res = $conn->query($app_order);

// $arr = []; // Initialize array

// if($app_order_res && $app_order_res->num_rows > 0){
//     while($app_order = $app_order_res->fetch_assoc()){
//         $arr[] = [
//             'order_id' => $app_order['product_id'], // Assuming 'product_id' is used as 'order_id'
//             'item_name' => $app_order['item_name'],
//             'item_price' => $app_order['item_price'],
//             'item_qty' => $app_order['item_qty'],
//             'total_qty' => $app_order['total']
//         ];
//     }
// } else {
//     echo "No order items found.";
// }

// // Check the output
// if (!empty($arr)) {
//     foreach ($arr as $item) {
//         echo "Total Quantity: ".$item['item_name']." [" . $item['total_qty'] . "]<br>";
//     }
// } else {
//     echo "No items to display.";
// }





// if($app_order_res->num_rows > 0){
//     while($row_app = $app_order_res->fetch_assoc()){
//         $item_name_app = $row_app['product_id'];
//         $item_qty_app = $row_app['total'];

//         // Prepare the statement to get data from over_items
//         $otc = "SELECT * FROM over_items WHERE over_name = ?";
//         $otc_res = $conn->prepare($otc);
//         $otc_res->bind_param("s", $item_name_app);
//         $otc_res->execute();

//         $rows = $otc_res->get_result();

//         $result_total = $rows->fetch_assoc();
//         $total_orders = $result_total['over_qty'] + $item_qty_app;
        
//         echo $result_total['over_name'] . " over_item [" . $result_total['over_qty'] ."]  app_item [" . $item_qty_app. "]" . $total_orders ;
//     }
// } else {
//     echo "No order items found.";
// }



$items_added = "SELECT *, i.*, 
                       (SELECT SUM(item_qty) 
                        FROM order_items oi 
                        WHERE oi.product_id = i.product_id) AS name_count 
                FROM inventory i 
                WHERE i.state = 0";
$item_inventory = $conn->query($items_added);

if ($item_inventory) {
    while ($row = $item_inventory->fetch_assoc()) {
        $product_name = $row['product_name'];
        $inv_product_qty = $row['product_qty'];
        $oi_qty = $row['name_count'];
        $product_id = $row['product_id'];

        // Query to get the over_qty from the over_items table
        $over_qty_query = "SELECT SUM(over_qty) AS over_qty 
                           FROM over_items 
                           WHERE over_name = '$product_name' 
                           OR item_id = '$product_id'";
        $over_qty_result = $conn->query($over_qty_query);

        if ($over_qty_result) {
            $over_qty_row = $over_qty_result->fetch_assoc();
            $over_qty = $over_qty_row['over_qty'] ?? 0;  // Default to 0 if null

            // Add the over_qty to the oi_qty
            $oi_qty += $over_qty;
        }

        echo $item_left = $inv_product_qty - $oi_qty;
        echo $oi_qty . "<br>";
        // Your further logic here, like updating values, displaying, etc.
    }
}
