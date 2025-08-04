<?php
include('session.php');

if (isset($_POST['add_otc'])) {
  $item_name = $_POST['item_name'];
  $item_price = $_POST['item_price'];
  $item_id = $_POST['item_id'];
  $item_desc = $_POST['item_desc'];

  $item_qty = 1;






  $sql_exist = "SELECT * FROM otc_item oi LEFT JOIN otc o ON oi.otc_id = o.otc_id WHERE oi.otc_void = 0 AND oi.otc_order = 0 AND oi.item_id = ? AND o.employee_id = ?";
  $stmt_exist = $conn->prepare($sql_exist);
  $stmt_exist->bind_param("si", $item_id, $id);
  $stmt_exist->execute();
  $result_exist = $stmt_exist->get_result();

  if ($result_exist->num_rows > 0) {
    $row_exist = $result_exist->fetch_assoc();
    $existing_otc_id = $row_exist['otc_id'];

    $update_qty_sql = "UPDATE otc_item SET otc_qty = otc_qty + ? WHERE otc_id = ?";
    $stmt_update_qty = $conn->prepare($update_qty_sql);
    $stmt_update_qty->bind_param("ii", $item_qty, $existing_otc_id);
    if ($stmt_update_qty->execute()) {
      // header('Location: otc.php');
      echo '<script src="../sweetalert2.min.js"></script>';
      echo '
      <script>
  document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
      title: "Item Quantity Order Added",
      text: "List of order updated.",
      icon: "info",
      timer: 2500,
      showConfirmButton: false
    });

    setTimeout(function() {
          window.location.href = "viewmenu?category_id=' . $category_last_order . '";
    }, 2600); // Redirect slightly after alert closes
  });
</script>';
    }
  } else {
    $sql_item = "INSERT INTO otc (item_id, employee_id) VALUES (?,?)";
    $result_otc = $conn->prepare($sql_item);
    $result_otc->bind_param('si', $item_id, $id);
    if ($result_otc->execute()) {
      $otc_id = $result_otc->insert_id;
      $items_otc = "INSERT INTO otc_item (item_name, item_price, otc_qty, item_desc, item_id, employee_id, otc_id) VALUES (?,?,?,?,?,?,?)";
      $item_otc = $conn->prepare($items_otc);
      $item_otc->bind_param('ssssiii', $item_name, $item_price, $item_qty, $item_desc, $item_id, $id, $otc_id);
      if ($item_otc->execute()) {
        // header('Location: otc.php');
        echo '<script src="../sweetalert2.min.js"></script>';
        echo '<script>
  document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
      title: "Item Order Inserted",
      text: "List of order updated.",
      icon: "success",
      timer: 2500,
      showConfirmButton: false
    });

    setTimeout(function() {
               window.location.href = "viewmenu?category_id=' . $category_last_order . '";
    }, 2600); // Redirect after 2.6 seconds
  });
</script>';
      }
    }
  }
}
