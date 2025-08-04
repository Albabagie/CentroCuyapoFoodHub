<?php
include('sidebar.php');

if(isset($_POST['additems_data'])) {
    $item_category = $_POST['category'];
    $item_name = $_POST['name'];
    $item_price = $_POST['price'];
    $item_quantity = $_POST['quantity'];  // The quantity to add to the stock
    $item_desc = $_POST['desc'];
    $item_status = $_POST['status'];

    // Check if the item already exists
    $check_item_sql = "SELECT * FROM inventory WHERE product_name = ?";
    $stmt_check = $conn->prepare($check_item_sql);
    $stmt_check->bind_param("s", $item_name);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Product exists, update the quantity by adding the new stock
        $row = $result_check->fetch_assoc();
        $new_quantity = $row['product_qty'] + $item_quantity;  // Add new stock to existing quantity

        // Ensure the new quantity is not negative
        if ($new_quantity < 0) {
            echo "<script>alert('Error: Stock cannot be negative!');</script>";
        } else {
            // Update the existing product record with the new quantity
            $update_sql = "UPDATE inventory SET product_qty = ?, product_price = ?, product_description = ?, state = ? WHERE product_name = ?";
            $stmt_update = $conn->prepare($update_sql);
            $stmt_update->bind_param("iisss", $new_quantity, $item_price, $item_desc, $item_status, $item_name);
            
            if ($stmt_update->execute()) {
                echo "Inventory updated successfully!";
            } else {
                echo "Error updating inventory.";
            }
        }
    } else {
        // Product does not exist, insert a new record with the specified quantity
        $insert_sql = "INSERT INTO inventory (product_category, product_name, product_qty, product_price, product_description, state) 
                       VALUES (?,?,?,?,?,?)";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("ssisss", $item_category, $item_name, $item_quantity, $item_price, $item_desc, $item_status);

        if ($stmt_insert->execute()) {
            $last_id = $stmt_insert->insert_id;
            
            if (!empty($_FILES['images']['name'][0])) {
                // Handle main image upload
                $targetDir = "../uploads/";
                $fileName = basename($_FILES["images"]["name"][0]);
                $targetFilePath = $targetDir . $fileName;

                if (move_uploaded_file($_FILES["images"]["tmp_name"][0], $targetFilePath)) {
                    // Update the product with the image
                    $image_sql = "UPDATE inventory SET item_img = ? WHERE product_id = ?";
                    $stmt_image = $conn->prepare($image_sql);
                    $stmt_image->bind_param("si", $targetFilePath, $last_id);
                    if ($stmt_image->execute()) {
                        echo "Product added with image successfully!";
                    } else {
                        echo "Error updating image.";
                    }
                    $stmt_image->close();
                } else {
                    echo "Failed to upload image.";
                }
            }
        } else {
            echo "Error inserting product.";
        }
    }

    // Redirect to another page after the action
    echo "<script>alert('Item is added');</script>";
    echo "<script>window.location.href = 'stalls.php';</script>";
    exit; 
}
?>
