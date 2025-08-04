<?php 
require_once 'connection.php';


$items = "SELECT * FROM inventory";
$sql_result = $conn->query($items);

    if($sql_result ){
            while($row = $sql_result->fetch_assoc()){
                $product_id = $row['product_id'];
            }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>testing</title>
</head>
<body>
    <div class="container my-5">
<table class="table table-striped table-bordered ">
<thead>
    <tr>
        <th>item_name</th>
        <th class="text-center">Left</th>
        <th class="text-center">Orders</th>
        <th class="text-center">Inventory Item</th>
    </tr>
</thead>
<tbody>
<!-- WHERE i.product_id = '$product_id' -->
<?php
$sql = "SELECT 
o.order_id, 
oi.item_name as name, 
total_orders, 
i.product_qty, 
i.product_id as in_id, 
oi.product_id as oi_id, 
i.product_description 
FROM 
orders o
LEFT JOIN 
order_items oi ON oi.order_id = o.order_id
LEFT JOIN 
inventory i ON i.product_id = oi.product_id 
LEFT JOIN 
(SELECT 
    oi.product_id, 
    COUNT(oi.item_qty) as total_orders
FROM 
    order_items oi 
GROUP BY 
    oi.product_id) counts ON counts.product_id = oi.product_id GROUP BY oi.item_name, i.product_id;
    ";

$result = $conn->query($sql);
// Check for errors
if(!$result) {
echo "Error: " . $conn->error;
} else {
$item_left = 0;

while($row = $result->fetch_assoc()) {
$name = $row['name'];
$inv_product_qty = $row['product_qty'];
$oi_qty = $row['total_orders'];
if($oi_qty) {
    $item_left = $inv_product_qty - $oi_qty;
}
?>
    <tr>
        <td ><?php echo $name; ?></td>
        <td class="text-center"><?php echo  $item_left;?></td>
        <td class="text-center"><?php echo $oi_qty;?></td>
        <td class="text-center"><?php echo $inv_product_qty;?></td>
    </tr>

<?php
}
}
?>
</tbody>
</table>
</div>
</body>
</html>
