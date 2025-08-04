
                      $order_items = $conn->query("SELECT o.order_id as order_m, o.status, o.order_date, o.customer_id, o.order_number, p.*, ot.*, oi.* 
                      FROM orders o 
                      LEFT JOIN order_total ot ON ot.order_id = o.order_id 
                      LEFT JOIN order_items oi ON oi.order_id = o.order_id 
                      LEFT JOIN payment p ON p.order_id = o.order_id
                      WHERE o.customer_id = '$id' AND DATE(o.order_date) = '$current_date'
                      GROUP BY o.order_id
                      ORDER BY o.order_date DESC");

                      if ($order_items->num_rows > 0) {
                        while ($row = $order_items->fetch_assoc()) {
                          $order_id = $row['order_id'];
                          $payment = $row['payment_status'];
                          $status = $row['status'];
                          echo '<tr>
                              <td class="text-center">  
                                  <span>' . $row['order_number'] . '</span>
                              </td>
                              <td>  
                                  <span class="text-center">' . $row['total_amt'] . '</span>
                              </td>
                              <td>
                                  <span class="text-center">' . date('Y-m-d', strtotime($row['order_date'])) . '</span>
                              </td>
                              <td>';
                          if ($row['status'] == 3) {
                            echo '<span class=" text-white p-sm-1 rounded text-center" style="background:rgb(215, 56, 94)">Out</span>';
                          } elseif ($row['status'] == 2) {
                            echo '<span class=" text-white p-sm-1 rounded text-center" style="background:rgb(5, 146, 18)">Ready</span>';
                          } else if ($row['status'] == 0) {
                            echo '<span class=" text-white p-sm-1 rounded text-center" style="background:rgb(97, 94, 252)">Waiting for Approval</span>';
                          } else if ($row['status'] == 1) {
                            echo '<span class=" text-white p-sm-1 rounded text-center" style="background:rgb(64, 132, 235)">Preparing</span>';
                          } else if ($row['status'] == 4) {
                            echo '<span class=" text-white p-sm-1 rounded text-center" style="background:rgb(153, 151, 151)">Cancelled</span>';
                          }
                          echo '</td>';
                          echo '<td>';
                          if ($payment == 'paid') {
                            echo '<div class="userDatatable-content ">Success</div>';
                          } else {
                            echo '<div class="userDatatable-content">Not paid</div>';
                          }
                          echo '</td>';
                          echo '<td>';
                          $order_number = $row['order_number'];
                          $exist_rating = $conn->query("SELECT * FROM rating 
                          WHERE order_number = '$order_number'");

                          $row_exist = $exist_rating->fetch_assoc();

                          if ($row_exist) {
                            if ($status == 1 && $payment == 'paid' && $row_exist['order_number'] == $order_number) {
                              echo '<button class="btn btn-light btn-square border border-lighten" data-bs-toggle="modal" data-bs-target="#view_order' . $row['order_number'] . '" disabled>Thank you! <i class="uil uil-heart text-danger" ></i></button>';
                            } elseif ($status == 3 && $payment == 'paid' && $row_exist['order_number'] == $order_number) {
                              echo '<button class="btn btn-light btn-square border border-lighten" data-bs-toggle="modal" data-bs-target="#view_order' . $row['order_number'] . '" disabled>Thank you! <i class="uil uil-heart text-danger" ></i></button>';
                            }
                          } elseif ($status == 3 && $payment == 'paid') {
                            echo '<button class="btn btn-warning btn-square text-white" data-bs-toggle="modal" data-bs-target="#view_order' . $row['order_number'] . '"><i class="uil uil-feedback"></i>Write Review</button>';
                          } elseif ($status == 0 && $payment == 'paid') {
                            echo '<button class="btn btn-gray btn-square text-white" data-bs-toggle="modal" data-bs-target="#order_item' . $row['order_number'] . '"><i class="uil uil-feedback">View Order</i></button>';
                          } else {
                            echo '<button class="btn btn-gray btn-square text-white" data-bs-toggle="modal" data-bs-target="#order_item' . $row['order_number'] . '" ><i class="uil uil-feedback">View Order</i></button>';
                          }
                          echo '</td>';



                          echo '<td >';
                          echo '<form method="POST">'; // specify method and action
                          echo '<input type="hidden" value="' . $order_id . '" name="order_id">';
                          echo '<button type="submit" name="cancelOrder" class="btn btn-danger btn-square text-white"';
                          if ($status != 0) {
                            echo ' disabled';
                          }
                          echo '><i class="uil uil-times"></i> Cancel';
                          echo '</button>';
                          echo '</form>';

                          echo '</td>';

                          echo '</tr>';


                          //modals

                          $order_number = $row['order_number'];
                          $order_date = $row['order_date'];
                          $order_items_result = $conn->query("SELECT 
                                      *, 
                                      (SELECT SUM(item_price) FROM order_items WHERE order_id = '$order_id') AS total
                                  FROM 
                                      orders o 
                                  LEFT JOIN 
                                      order_items oi 
                                  ON 
                                      oi.order_id = o.order_id 
                                  WHERE 
                                      o.order_id = '$order_id';
                                  ");

                          echo '<div class="modal fade" id="view_order' . $row['order_number'] . '" tabindex="-1" aria-labelledby="view_order' . $row['order_number'] . 'Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="view_order' . $row['order_number'] . 'Label">We appreciate your feedback!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form  method="POST">
                        <div class="modal-body">
                            <div class="new-member-modal">
                                <div class="form-group mb-20">
                                    <h3 class="text-center">Order Receipt</h3>
                                    <div class="w-100 text-center">
                                        <span class="text-dark ">Food Hub | Centro Cuyapo</span><br>
                                        <span class="text-dark  text-center">' . date('M - d - Y', strtotime($order_date)) . '</span><br>
                                        <h2 class="text-dark  text-center">order # ' . $row['order_number'] . '</h2>
                                    </div>
                                    <div class="card row">
                                        <div class="card-header">
                                            <h5>Items</h5>
                                            <h5>Qty</h5>
                                            <h5>Price</h5>
                                        </div>
                                        <div class="card-body">';

                          // Output order items and star ratings
                          $counter = 1;
                          while ($item_row = $order_items_result->fetch_assoc()) {
                            $item_name = $item_row['item_name'];
                            $item_qty = $item_row['item_qty'];
                            $item_price = $item_row['item_price'];
                            $total = $item_row['total'];
                            echo '<div class="card my-sm-2 p-2 ">';
                            echo '<div class="text-dark m-2 d-flex flex-col justify-content-between position-relative ">';
                            // echo '<input type="hidden" value="' . $row['order_number'] . '">';
                            echo '<span class="col-md-6">' . $item_row['item_name'] . '</span>';
                            echo '<span class="col-md-2">' . $item_row['item_qty'] . '</span>';
                            echo '<span class="col-md-4 text-end">' . $item_row['item_price'] . '</span>';
                            echo '</div>';

                            echo '<span class="text-center">How would you rate the value of the food? </span>';


                            echo '<div class="rating">';


                            for ($i = 5; $i >= 1; $i--) {
                              $star_id = 'star_' . $row['order_number'] . '_' . $counter . '_' . $i;
                              echo '<input type="radio" id="' . $star_id . '" name="rating_' . $counter . '" value="' . $i . '" required>';
                              echo '<label for="' . $star_id . '"></label>';
                            }
                            echo '</div>';


                            echo '<div>';

                            echo '<input type="hidden" name="customer_id_' . $counter . '" value="' . $id . '">';
                            echo '<input type="hidden" name="item_id_' . $counter . '" value="' . $item_row['product_id'] . '">';
                            echo '<input class="form-control w-100" type="text" id="chosenWordInput_' . $counter . '" name="feedback_' . $counter . '" placeholder="Feedback">';
                            echo '<input type="hidden" name="order_number_' . $counter . '" value="' . $row['order_number'] . '">';
                            echo '<input type="hidden" name="order_id_' . $counter . '" value="' . $row['order_id'] . '">';
                            echo '</div> ';
                            echo '</div>';

                            $counter++;
                          }

                          echo '
                         
                          </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">';
                          if ($row['status'] != 1 && $row['status'] != 2 && $row['status'] != 3) {
                            echo '<button class="btn btn-gray mx-sm-2" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                  <button class="btn btn-warning mx-sm-2 text-white" disabled><i class="uil uil-star"></i>Give Rating</button>';
                          } else {
                            echo '<button class="btn btn-gray mx-sm-2" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-warning mx-sm-2 text-white" name="submit_ratings"><i class="uil uil-star"></i>Give Ratings</button>';
                          }

                          echo '</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>';

                          // modal on view order
                          echo '<div class="modal fade modal-order" id="order_item' . $row['order_number'] . '" tabindex="-1" aria-labelledby="view_order' . $row['order_number'] . 'Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="view_order' . $row['order_number'] . 'Label">Thank you for choosing us!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  method="POST">
                <div class="modal-body">
                    <div class="new-member-modal">
                        <div class="form-group mb-20">
                            <h3 class="text-center">' . ($status == 4 ? 'Cancelled' : 'Your') . ' Orders</h3> 
                            <div class="w-100 text-center">
                                <span class="text-dark ">Food Hub | Centro Cuyapo</span><br>
                                <span class="text-dark  text-center">' . date('M - d - Y', strtotime($order_date)) . '</span><br>
                                <h2 class="text-dark  text-center">order # ' . $row['order_number'] . '</h2>
                            </div>
                            <div class="card row">
                                <div class="card-header">
                                    <h5>Items</h5>
                                    <h5>Qty</h5>
                                    <h5>Item Price</h5>
                                    <h5>Total</h5>
                                </div>
                                <div class="card-body">';

                          //dito bukas query of item

                          $orders = "SELECT * FROM orders WHERE order_number ='" . $row['order_number'] . "'";
                          $result = $conn->query($orders);
                          if ($result) {
                            while ($items = $result->fetch_assoc()) {
                              $order_id = $items['order_id'];
                            }
                          }



                          $item = "SELECT *, item_price * item_qty AS total FROM order_items WHERE order_id = '$order_id'";
                          $result_item = $conn->query($item);

                          if ($result_item) {

                            $status_order = false;
                            $total_ = 0;
                            while ($row_items = $result_item->fetch_assoc()) {
                              $total_ += $row_items['total'];

                              if ($status == 4) {
                                $status_order = true;
                              }
                              echo '<div class="d-flex flex-col">
                              <span class="col-md-3"> ' . $row_items['item_name'] . '</span>
                              <span class="col-md-3"> ' . $row_items['item_qty'] . '</span>
                              <span class="col-md-3 text-end"> ' . number_format($row_items['item_price']) . '</span>
                              <span class="col-md-3 text-end"> ' . number_format($row_items['item_price'] * $row_items['item_qty']) . '</span>
                              </div>';
                            }
                          }

                          echo '
                            <div class="my-4 mx-4 d-flex flex-col justify-content-between">
                            <h3 class="text-dark">Total:</h3>
                            <span class="text-dark">' . number_format($total_) . '</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">';
                          $tbill = $total_ * 100;

                          if ($payment == 'paid') {
                            echo '
                          <form method="POST">
                          <input type="hidden" name="order_id" value="' . $order_id . '">
                          <input type="hidden" name="bill" value="' . $order_id . '">
                            <button type="submit" name="pay" class="btn btn-secondary disabled">Paid</button>
                            </form>
                            ';
                          } else {
                            $sql_orders = "SELECT * FROM order_items WHERE order_id = '$order_id'";
                            $result_row = $conn->query($sql_orders);

                            if ($result_row->num_rows > 0) {
                              echo '<form method="POST">';
                              echo '<input type="hidden" name="order_id" value="' . $order_id . '">';
                              echo '<input type="hidden" name="bill" value="' . $tbill . '">';

                              while ($row_of = $result_row->fetch_assoc()) {
                                echo '<input type="hidden" value="' . $row_of['item_name'] . '" name="items[]">';
                                echo '<input type="hidden" value="' . $row_of['item_qty'] . '" name="item_qty[]">';
                                echo '<input type="hidden" value="' . $row_of['item_price'] . '" name="price[]">';
                              }

                              // echo '<button type="button" name="payment_method_online" class="btn btn-shadow-third btn-primary mx-2"  data-toggle="modal" data-target="#payments' . $row['order_number'] . '">Make Payment</button>';
                              if ($wallet_qr != '') {

                                // echo '<button type="button" name="payment_method_online" class="btn btn-shadow-third btn-primary mx-2"' . $row['order_number'] . '" data-toggle="modal" data-target="#payments" onClick="noticePayment()">Online Payment</button>';
                                // echo '<button "'.$status_order ? 'disabled': '' .' " type="button" class="btn btn-shadow-third btn-primary mx-2" onClick="handlePayment(\'' . $row['order_number'] . '\')">Online Payment</button>';
                                echo '<button type="button" class="btn btn-shadow-third btn-primary mx-2" ' . ($status_order ? 'disabled' : '') . ' onClick="handlePayment(\'' . $row['order_number'] . '\')">Online Payment</button>';
                              } else {
                                echo '<button type="submit" name="not_available" class="btn btn-shadow-third btn-primary mx-2">Online Payment</button>';
                              }
                              echo '<button type="submit" name="payment_method_cash" class="btn btn-shadow-third btn-success mx-2"  ' . ($status_order ? 'disabled' : '') . '>Cash Payment</button>';


                              echo '</form>';
                            }
                          }



                          echo '</div>
                </div>
            </form>
        </div>
    </div>
    </div>
    </div>
</div>';





                          // payments
                          echo '<div class="modal fade" id="payments" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="paymentsLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="paymentsLabel">Payment | Cuyapo Food Hub</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
            <div class="text-danger mx-2 my-2">
            <p>
             * Please head to counter after using online payment to be verified. Thank youu*
             </p>    
            </div>
              <div class="modal-body">';
                          if ($wallet_qr != '') {

                            echo '
                              <div class="my-5">
                                <label for="payment">Scan QR to Pay Online:</label>
                              </div>
                                          
                              <div class="container-md">
                            
                            
                            <img src="../uploads/' . $wallet_qr . '"   style="max-width: 100%; max-height: 300px;"/>';
                          } else {
                            echo 'Online Payment is not right available';
                          }


                          echo '</div>

                           <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- modal end payment -->
    </div>
  </div>
</div>';
                        }
                      }
                      // else {
                      //   echo '<div class="col-lg-12">';
                      //   echo '<p colspan="5"><div class="w-100 d-flex justify-content-center">No Order Yet.</div></p>';
                      //   echo '</div>';
                      // }

                      ?>

                </div>