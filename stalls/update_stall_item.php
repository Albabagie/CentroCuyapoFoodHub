
<?php
include('../connection.php');

if (isset($_POST['updateOrders'])) {
    $id = (int) $_POST['id']; // Ensure it's an integer
    $table = $_POST['table'];
    $id_column = $_POST['id_column'];

    // Prepare the update query for regular orders
    $sql = "UPDATE orders SET stall_done = 1 WHERE order_item_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Item updated successfully in orders."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update item in orders."]);
        }
    }

    // Prepare the update query for OTC orders
    $sql_otc = "UPDATE otc_orders SET stall_done = 1 WHERE order_item_id = ?";
    $stmt_otc = $conn->prepare($sql_otc);
    if ($stmt_otc) {
        $stmt_otc->bind_param("i", $id);
        if ($stmt_otc->execute()) {
            echo json_encode(["success" => true, "message" => "Item updated successfully in OTC orders."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update item in OTC orders."]);
        }
    }
    $stmt->close();
    $stmt_otc->close();
    exit;
} else {
    echo json_encode(["success" => false, "message" => "Update failed."]);
}
?>
