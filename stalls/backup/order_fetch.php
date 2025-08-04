<?php
include('session.php');

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
                       