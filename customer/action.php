<?php

include('../connection.php');

// header('Content-Type: application/json');

// Include DB connection here
// $conn = new mysqli(...);

$data = json_decode(file_get_contents('php://input'), true);
$cart_id = $data['cart_id'] ?? null;


if ($cart_id !== null) {
    $cart_id = (int) $cart_id;

    $sql = "UPDATE cart_item SET item_void = 1 WHERE cart_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cart_id);

    if ($stmt->execute()) {
        $sql_update = "UPDATE cart SET cart_status = 1 WHERE cart_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $cart_id);

        if ($stmt_update->execute()) {

            

            echo json_encode([
                'success' => true,
                'message' => 'Item removed and order updated.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update order status.'
            ]);
        }
        $stmt_update->close();
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to mark item as void.'
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No cart_id provided in request.'
    ]);
}
