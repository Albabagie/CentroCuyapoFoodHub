<?php
include('sidebar.php');

$over_id = $_GET['over_id'];
$number = $conn->query("SELECT over_number FROM over_orders WHERE over_id = '$over_id'");
if ($number) {
    $numbers = $number->fetch_assoc()['over_number'];
} else {
    echo "Error: " . $conn->error;
}

// if (isset($_POST['prepareorder'])) {
//     $over_id = $_POST['over_id'];
//     if (!empty($over_id)) {
//         $order_update = "UPDATE over_orders SET over_status = 1 WHERE over_id = ?";
//         $result_up = $conn->prepare($order_update);
//         $result_up->bind_param("i", $over_id);
//         if ($result_up->execute()) {
//             echo '<script>
//                         document.addEventListener("DOMContentLoaded", function() {
//                             Swal.fire({
//                                 title: "Preparing Order.",
//                                 text: "nice.",
//                                 icon: "success"
//                             });
//                         });
//                         setTimeout(function() {
//                             window.location.href = "index.php";
//                         }, 2000);
//                     </script>';
//         }
//     }
// }
if (isset($_POST['prepareorder'])) {
    $order_id = $_POST['order_id'];
    $order_count = $_POST['item_count'];
    $total = $_POST['total'];
    $change = $_POST['change'];
    $tendered_amount = $_POST['tendered'];

    $payment_date = date('Y-m-d');
    $payment_method = 'otc';
    $payment_status = 'paid';

    if (!empty($order_id)) {
        $order_update = "UPDATE otc SET otc_status = 1 WHERE otc_id = ?";
        $result_up = $conn->prepare($order_update);
        $result_up->bind_param("i", $order_id);
        if ($result_up->execute()) {
            $otc_items = "UPDATE otc_item SET otc_order = 1 WHERE otc_order = 0";
            $result_item_otc = $conn->query($otc_items);

            if ($result_item_otc) {
                $sql = "INSERT INTO payment_otc (otc_order_id, payment_otc_date, payment_otc_method, payment_otc_status, payment_otc_amount) VALUES (?,?,?,?,?)";
                $result_order = $conn->prepare($sql);
                $result_order->bind_param('issss', $order_id, $payment_date, $payment_method, $payment_status, $total);

                if ($result_order->execute()) {
                    $payment_id = $conn->insert_id;
                    $change_tendered = "INSERT INTO tendered (payment_id,item_count,total,change_amt,tend_amt) VALUES (?,?,?,?,?)";
                    $tend_res = $conn->prepare($change_tendered);
                    $tend_res->bind_param('iiiii', $payment_id, $order_count, $total, $change, $tendered_amount);
                    if ($tend_res->execute()) {
                        $listed = "UPDATE over_orders SET over_status = 1 WHERE over_id = $order_id";
                        $result_listed = $conn->query($listed);
                        if ($result_listed) {
                            echo '<script>
                            document.addEventListener("DOMContentLoaded", function() {
                                Swal.fire({
                                    title: "Preparing Order.",
                                    text: "nice.",
                                    icon: "success"
                                });
                            });
                            setTimeout(function() {
                                window.location.href = "index";
                            }, 1000);
                        </script>';
                        }
                    } else {
                        echo 'Failed tendered';
                    }
                } else {
                    echo 'Failed payment';
                }
            } else {
                echo 'Failed Insertion';
            }
        }
    }
}
?>

<div class="contents">
    <div class="crm demo6 mb-25">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Order</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb-main user-member justify-content-sm-between ">
                            <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
                                <div class="d-flex align-items-center user-member__title justify-content-center me-sm-25">
                                    <h2 class="text-capitalize fw-500 breadcrumb-title">Orders</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!--  -->

                <?php
                $sql = "SELECT * FROM over_orders o 
               LEFT JOIN over_items oi ON oi.over_id = o.over_id 
               LEFT JOIN over_total ot ON oi.over_id = ot.over_id 
               WHERE o.over_id = '$over_id' AND over_status = 0 
               GROUP BY oi.item_id";

                $result = $conn->query($sql);
                $total = 0;
                $item = [];

                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $order_total = $row['over_price'] * $row['over_qty'];
                        $total += $order_total;

                        // Store the item details in the array
                        $item[] = [
                            'over_name' => $row['over_name'],
                            'over_qty' => $row['over_qty'],
                            'over_price' => $row['over_price'],
                            'order_total' => $order_total,
                            'over_number' => $row['over_number']
                        ];
                    }
                }
                $item_count = count($item);
                // Display the order details
                if (!empty($item)) {
                    echo '<div class="d-flex justify-content-center">
                   <div class="card col-lg-8 px-lg-3 text-center reciept">
                       <div class="d-flex justify-content-end">
                           <div class="text-white p-2 bg-primary rounded w-25 mt-2">
                               <span class="text-white">Order Number</span>
                               <h4 class="text-white">' . $item[0]['over_number'] . '</h4>
                           </div>
                       </div>
                       <form method="POST">
                           <table class="table">
                               <thead>
                                   <tr>
                                       <td class="text-start px-md-4"><h5>Name</h5></td>
                                       <td><h5>Order Qty</h5></td>
                                       <td><h5>Item Price</h5></td>
                                       <td><h5>Total</h5></td>
                                   </tr>
                               </thead>
                               <tbody>';
                    foreach ($item as $i) {
                        echo '<tr>
                       <td class="text-start w-50 h-100">
                           <input type="text" class="container text-lowercase text-center border-0 w-100" value="' . $i['over_name'] . '" name="name[]" readonly>
                       </td>
                       <td>
                           <input type="number" class="text-center border-0 w-25" value="' . $i['over_qty'] . '" name="qty[]" readonly>
                       </td>
                       <td>' . $i['over_price'] . '</td>
                       <td>' . number_format($i['order_total'], 2) . '</td>
                     </tr>';
                    }
                    echo '        </tbody>
                           </table>
                           <div class="w-100 d-flex justify-content-sm-around text-end">
                               <div class="col-md-3">
                                   <h4>Total:</h4>
                               </div>
                               <div class="col-lg-6">
                                   <p>
                                       <input class="border-0 text-center col-lg-4" type="number" name="total"
                                           value="' . number_format($total, 2) . '" readonly>
                                   </p>
                               </div>
                           </div>
                           <div class="card-footer d-flex justify-content-end border-0 mt-lg-5">
                                 <button type="button" class="btn btn-primary btn-square w-50 px-md-2 my-md-2 fs-6" data-bs-toggle="modal" data-bs-target="#prepare_order">Prepare Order</button>
                           </div>
                   </div>
                   </form>
               </div>';

                    echo '<div class="modal fade" id="prepare_order" tabindex="-1" aria-labelledby="prepare_orderLabel" aria-hidden="true">
               <div class="modal-dialog">
                   <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title" id="view_orderLabel">Order Payment | Food Hub</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                   </div>';
                    echo '<form method="POST">';
                    echo '<div class="modal-body">
                           <input type="hidden" value="' . $over_id . '" name="order_id">
                           <label class="form-group"> 
                           <span class="my-3">Total Items: </span>
                           <input class="form-control mt-2" type="text" readonly value="' . $item_count . '" name="item_count">
                           </label>
                           <label class="form-group"> 
                           <span class="my-3">Total Payment: </span>
                           <input class="form-control mt-2" type="number" readonly value="' .  $total  . '" id="total_payment" name="total">
                           </label>
                           <div class="card w-50 position-absolute px-3 py-2">
                            <h5> Ordered Items</h5>
                            <ol class="gap-2">';
                    foreach ($item as $list) {
                        echo '<li class="text-dark text-1xl ">' . $list['over_name'] . ' | <span class="mx-2">' . $list['over_price'] . '</span></li>';
                    }
                    echo  '
                           
                            </ol>
                           </div>
                  <br>
                  <br>
                  <div class="d-flex justify-content-end">
                  <label class="form-group"> 
                  <span class="my-3">Change: </span>
                  <input class="form-control mt-2" type="number" readonly id="change" name="change"> 
                  </label>
                  </div>
                   <div class="d-flex justify-content-end">
                  <label class="form-group"> 
                  <span class="my-3">Payment Tendered: </span>
                  <input class="form-control mt-2" type="number" placeholder="0" name="tendered" id="tendered">
                  </label>
                  </div>
                  <div class="d-flex justify-content-end">
                  <span id="message" class="text-danger"></span>
              </div>
                  </div>';
                    echo '<div class="modal-footer">
                       <button type="submit" name="prepareorder" class="btn btn-primary" id="prepareButton">Prepare</button>

                   </div>';

                    echo '</div>
                   </form>
                   </div>
                   </div>';
                }









                ?>

                <!--  -->
            </div>
        </div>
    </div>
    
    </main>
    <div id="overlayer">
        <div class="loader-overlay">
            <div class="dm-spin-dots spin-lg">
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
            </div>
        </div>
    </div>
    <div class="overlay-dark-sidebar"></div>
    <div class="customizer-overlay"></div>
    <div class="customizer-wrapper">
        <div class="customizer">
            <div class="customizer__head">
                <h4 class="customizer__title">Customizer</h4>
                <span class="customizer__sub-title">Customize your overview page layout</span>
                <a href="#" class="customizer-close">
                    <img class="svg" src="img/svg/x2.svg" alt>
                </a>
            </div>
            <div class="customizer__body">
                <div class="customizer__single">
                    <h4>Layout Type</h4>
                    <ul class="customizer-list d-flex layout">
                        <li class="customizer-list__item">
                            <a href="http://demo.dashboardmarket.com/hexadash-html/ltr" class="active">
                                <img src="img/ltr.png" alt>
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </li>
                        <li class="customizer-list__item">
                            <a href="http://demo.dashboardmarket.com/hexadash-html/rtl">
                                <img src="img/rtl.png" alt>
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="customizer__single">
                    <h4>Sidebar Type</h4>
                    <ul class="customizer-list d-flex l_sidebar">
                        <li class="customizer-list__item">
                            <a href="#" data-layout="light" class="dark-mode-toggle active">
                                <img src="img/light.png" alt>
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </li>
                        <li class="customizer-list__item">
                            <a href="#" data-layout="dark" class="dark-mode-toggle">
                                <img src="img/dark.png" alt>
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="customizer__single">
                    <h4>Navbar Type</h4>
                    <ul class="customizer-list d-flex l_navbar">
                        <li class="customizer-list__item">
                            <a href="#" data-layout="side" class="active">
                                <img src="img/side.png" alt>
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </li>
                        <li class="customizer-list__item top">
                            <a href="#" data-layout="top">
                                <img src="img/top.png" alt>
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </li>
                        <li class="colors"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.all.min.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgYKHZB_QKKLWfIRaYPCadza3nhTAbv7c"></script>
    <script src="js/plugins.min.js"></script>
    <script src="js/script.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tenderedInput = document.getElementById('tendered');
            const changeInput = document.getElementById('change');
            const totalPaymentInput = document.getElementById('total_payment');
            const messageSpan = document.getElementById('message');
            const prepareButton = document.getElementById('prepareButton');

            tenderedInput.addEventListener('input', function() {
                const tendered = parseFloat(tenderedInput.value) || 0;
                const totalPayment = parseFloat(totalPaymentInput.value) || 0;

                if (tenderedInput.value === '') {
                    changeInput.value = '0.00';
                    messageSpan.textContent = '';
                    prepareButton.disabled = true;
                } else {
                    const change = tendered - totalPayment;
                    changeInput.value = change.toFixed(2);

                    if (tendered < totalPayment) {
                        messageSpan.textContent = 'The tendered amount is not enough.';
                        messageSpan.style.color = 'red';
                        prepareButton.disabled = true;
                    } else {
                        messageSpan.textContent = '';
                        prepareButton.disabled = false;
                    }
                }
            });

            // Initial state of the button
            prepareButton.disabled = true;
        });
    </script>
    </body>


    </html>