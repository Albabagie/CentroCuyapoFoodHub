<?php
include('sidebar.php');

// $order_id = $_GET['order_id'];

$number = $conn->query("SELECT order_number FROM orders WHERE order_id = " . $_GET['order_id']);
if ($number) {
    $numbers = $number->fetch_assoc()['order_number'];
} else {
    echo "Error: " . $conn->error;
}

if (isset($_POST['paidorder'])) {
    $order_id = $_POST['order_id'];
    $order_count = $_POST['item_count'];
    $total = $_POST['total'];
    $change = $_POST['change'];
    $tendered_amount = $_POST['tendered'];
    $payment_id = $_POST['payment_id'];

    if (!empty($order_id)) {
        $order_online = "UPDATE orders SET status = 1 WHERE order_id = ?";
        $res_ol = $conn->prepare($order_online);
        $res_ol->bind_param('i', $order_id);

        if ($res_ol->execute()) {
            $insert_tend = "INSERT INTO tendered (payment_id, item_count, total, change_amt, tend_amt) VALUES (?,?,?,?,?)";
            $res_tend = $conn->prepare($insert_tend);
            $res_tend->bind_param('iiiii', $payment_id, $order_count, $total, $change, $tendered_amount);

            if ($res_tend->execute()) {
                echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "Preparing Order.",
                        text: "nice.",
                        icon: "success"
                    });
                });
                setTimeout(function() {
                    window.location.href = "index.php";
                }, 1000);
                </script>';
            }
        }
    }
}


if (isset($_POST['prepareorder'])) {
    $order_id = $_POST['order_id'];
    $order_count = $_POST['item_count'];
    $total = $_POST['total'];
    $change = $_POST['change'];
    $tendered_amount = $_POST['tendered'];

    $payment_date = date('Y-m-d');
    $payment_status = 'paid';

    $payment_method = $_POST['payment_method'];


    if (!empty($order_id)) {
        $order_update = "UPDATE orders SET status = 1 WHERE order_id = ?";
        $result_up = $conn->prepare($order_update);
        $result_up->bind_param("i", $order_id);
        if ($result_up->execute()) {

            $sql = "INSERT INTO payment (order_id, payment_date, payment_method, payment_status, paid_amount) VALUES (?,?,?,?,?)";
            $result_order = $conn->prepare($sql);
            $result_order->bind_param('issss', $order_id, $payment_date, $payment_method, $payment_status, $total);

            if ($result_order->execute()) {
                $payment_id = $conn->insert_id;
                $change_tendered = "INSERT INTO tendered (payment_id,item_count,total,change_amt,tend_amt) VALUES (?,?,?,?,?)";
                $tend_res = $conn->prepare($change_tendered);
                $tend_res->bind_param('iiiii', $payment_id, $order_count, $total, $change, $tendered_amount);
                if ($tend_res->execute()) {
                    echo '<script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                title: "Preparing Order.",
                                text: "nice.",
                                icon: "success"
                            });
                        });
                        setTimeout(function() {
                            window.location.href = "index.php";
                        }, 1000);
                    </script>';
                }
            }
        }
    }
}

?>

<div class="contents vh-100" style="background:rgb(252,250,241)">
    <div class="crm demo6 mb-25">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <h4 class="text-capitalize breadcrumb-title">Orders</h4>
                        <div class="breadcrumb-action justifdy-content-center flex-wrap">
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
                <?php
                $order_id = $_GET['order_id'];
                // Use prepared statements to prevent SQL injection
                $sql = "SELECT *, COUNT(oi.order_id) as item_listed 
                        FROM orders o 
                        LEFT JOIN order_items oi ON oi.order_id = o.order_id 
                        LEFT JOIN payment p ON p.order_id = o.order_id
                        LEFT JOIN payment_otc po ON po.otc_order_id = o.order_id
                        WHERE o.order_id = ? AND o.status = 0
                        GROUP BY o.order_number, oi.product_id
                        ORDER BY o.order_number";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $order_id);
                $stmt->execute();
                $result = $stmt->get_result();

                $orders = array();

                while ($row = $result->fetch_assoc()) {
                    $order_number = $row['order_number'];
                    $order_id = $row['order_id'];

                    if (!isset($orders[$order_number])) {
                        $orders[$order_number] = array(
                            'order_number' => $order_number,
                            'order_id' => $order_id,
                            'items' => array(),
                            'total' => 0
                        );

                        $payment_id = $row['payment_id'];
                        $online_payment = $row['payment_method'];
                        $amount_paid = $row['paid_amount'];
                        $status_payment = $row['payment_status'];
                        $listed_item = $row['item_listed'];
                    }



                    $order_total = $row['item_price'] * $row['item_qty'];
                    $orders[$order_number]['total'] += $order_total;

                    $orders[$order_number]['items'][] = array(
                        'name' => $row['item_name'],
                        'quantity' => $row['item_qty'],
                        'price' => $row['item_price'],
                        'total' => $order_total
                    );
                }
                // Display the orders
                foreach ($orders as $order) {
                    $total = $order['total'];
                    $order_id = $_GET['order_id']

                    // Render the HTML for the order
                ?>
                    <div class="d-flex justify-content-center">
                        <div class="card col-lg-8 px-lg-3 text-center receipt" style="background:rgb(248,239,212)">
                            <div class="card-header text-dark" style="background:rgb(248,239,212)">
                                <?= strtoupper($status_payment); ?>
                            </div>
                            <div class="d-flex justify-content-end">
                                <div class="text-white p-2 rounded w-25 mt-2" style="background:rgb(247,103,7)">
                                    <span class="text-white">Order Number</span>
                                    <h4 class="text-white"><?= $order['order_number']; ?></h4>
                                </div>
                            </div>
                            <div class="card" style="background:rgb(248,239,212)">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <td class="text-start px-md-4">
                                                <h5>Name</h5>
                                            </td>
                                            <td>
                                                <h5>Order Qty</h5>
                                            </td>
                                            <td>
                                                <h5>Item Price</h5>
                                            </td>
                                            <td>
                                                <h5>Total</h5>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($order['items'] as $item): ?>
                                            <tr>
                                                <td class="text-start w-50 h-100">
                                                    <div class="form-control fs-5 text-lowercase border-0 container"
                                                        style="background:rgb(248,239,212)" readonly>
                                                        <?= htmlspecialchars($item['name']); ?>
                                                    </div>
                                                </td>
                                                <td><input type="number" class="text-center border-0 w-25"
                                                        value="<?= intval($item['quantity']); ?>"
                                                        style="background:rgb(248,239,212)" readonly></td>
                                                <td><?= number_format($item['price'], 2); ?></td>
                                                <td><?= $item['total'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <div class="w-100 d-flex justify-content-sm-around text-end">
                                    <div class="col-md-3">
                                        <h4>Total:</h4>
                                    </div>
                                    <div class="col-lg-6">
                                        <p><input class="border-0 text-center col-lg-4" type="number" name="total"
                                                value="<?= $total ?>" style="background:rgb(248,239,212)" readonly></p>
                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-end border-0 mt-lg-5"
                                    style="background:rgb(248,239,212)">
                                    <?php if ($status_payment == 'paid' && $online_payment == 'Gcash'): ?>
                                        <button type="button" class="btn btn-square w-50 px-md-2 my-md-2 fs-6 text-white"
                                            data-bs-toggle="modal" data-bs-target="#paid_order"
                                            style="background:rgb(19, 39, 67)">Paid Order</button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-square w-50 px-md-2 my-md-2 fs-6 text-white"
                                            data-bs-toggle="modal" data-bs-target="#prepare_order<?php echo $order_id ?>"
                                            style="background:rgb(19, 39, 67)">Approve Order</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal otc -->
                    <div class="modal fade" id="prepare_order<?php echo $order_id ?>" tabindex="-1"
                        aria-labelledby="prepare_orderLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" style="background:rgb(247,103,7)">
                                    <h5 class="modal-title text-white" id="view_orderLabel">Order Payment | Food Hub</h5>
                                    <button type="button" class="btn-close btn-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body" style="background:rgb(248,239,212)">
                                        <input type="hidden" value="<?php echo $order_id; ?>" name="order_id">
                                        <label class="form-group">
                                            <span class="my-3">Total Items: </span>
                                            <input class="form-control mt-2" type="text" readonly
                                                value="<?= $listed_item; ?>" name="item_count">
                                        </label>
                                        <label class="form-group">
                                            <span class="my-3">Total Payment: </span>
                                            <input class="form-control mt-2" type="number" readonly value="<?= $total ?>"
                                                id="total_payment" name="total">
                                        </label>
                                        <br>
                                        <br>

                                        <div
                                            class="col-lg-8 border-1 px-2 py-2 position-relative rounded bg-white mh-v-25 overflow-y-scroll">
                                            <h5 class="text-dark">Item List:</h5>
                                            <div class="container">
                                                <table class="table table-borderless">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Qty</th>
                                                            <th>Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($order['items'] as $order_list): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($order_list['name']); ?></td>
                                                                <td><?= intval($order_list['quantity']); ?></td>
                                                                <td><?= number_format($order_list['price'], 2); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                        <div class="form-group my-4">
                                            <label class="font-weight-bold">Select Payment Method:</label>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" id="cash" name="payment_method"
                                                    value="cash">
                                                <label class="form-check-label" for="cash">Cash</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" id="online"
                                                    name="payment_method" value="online">
                                                <label class="form-check-label" for="online">Online</label>
                                            </div>
                                        </div>


                                        <!-- Cash Payment Section -->
                                        <div id="cash-section" style="display:none;">
                                            <div class="d-flex justify-content-end">
                                                <label class="form-group">
                                                    <span class="my-3">Change: </span>
                                                    <input class="form-control mt-2" type="number" readonly id="change"
                                                        name="change">
                                                </label>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <label class="form-group">
                                                    <span class="my-3">Payment Tendered: </span>
                                                    <input class="form-control mt-2" type="number" placeholder="0"
                                                        name="tendered" id="tendered">
                                                </label>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <span id="message" class="text-danger"></span>
                                            </div>

                                            <div class="modal-footer" style="background:rgb(247,103,7)">
                                                <button type="submit" name="prepareorder" class="btn text-white"
                                                    id="prepareButton" style="background:rgb(19, 39, 67)">Prepare</button>
                                            </div>
                                        </div>



                                        <?php
                                        $total_paid_amount = 0;
                                        foreach ($order['items'] as $order_list):
                                            $total_paid_amount += $order_list['price'];
                                        ?>

                                            <div id="online-section" style="display:none;">
                                                <input class="form-control mt-2" type="hidden" readonly name="change"
                                                    value="<?php echo $total_paid_amount ?>">
                                                <div class="modal-footer" style="background:rgb(247,103,7)">
                                                    <button type="submit" name="prepareorder" class="btn text-white"
                                                        style="background:rgb(19, 39, 67)">Prepare</button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- modal paid -->
                    <div class="modal fade" id="paid_order" tabindex="-1" aria-labelledby="paid_orderLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" style="background:rgb(247,103,7)">
                                    <h5 class="modal-title text-white" id="paid_orderLabel">Order Paid | Food Hub</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" value="<?= $order['order_id'] ?>" name="order_id">
                                        <input type="hidden" value="<?= $payment_id ?>" name="payment_id">
                                        <label class="form-group">
                                            <span class="my-3">Total Items: </span>
                                            <input class="form-control mt-2" type="text" readonly
                                                value="<?= count($order['items']) ?>" name="item_count">
                                        </label>
                                        <label class="form-group">
                                            <span class="my-3">Total Payment: </span>
                                            <input class="form-control mt-2" type="number" readonly
                                                value="<?= $order['total'] ?>" id="total_payment" name="total">
                                        </label>
                                        <br>
                                        <br>

                                        <div
                                            class="col-lg-8 border-1 px-2 py-2 position-relative rounded bg-white mh-v-25 overflow-y-scroll">
                                            <h5 class="text-dark">Item List:</h5>
                                            <div class="container">
                                                <table class="table table-borderless">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Qty</th>
                                                            <th>Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($order['items'] as $order_list): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($order_list['name']); ?></td>
                                                                <td><?= intval($order_list['quantity']); ?></td>
                                                                <td><?= number_format($order_list['price'], 2); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <label class="form-group">
                                                <input class="form-control mt-2" type="hidden" readonly id="change"
                                                    value="0" name="change">
                                            </label>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <label class="form-group">
                                                <span class="my-3">Payment Tendered: </span>
                                                <input class="form-control mt-2" type="number"
                                                    value="<?php echo $amount_paid ?>" name="tendered" id="tendered"
                                                    readonly>
                                            </label>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <span id="message" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="background:rgb(247,103,7)">
                                        <button type="submit" name="paidorder" class="btn text-white" id="prepareButton"
                                            style="background:rgb(19, 39, 67)">Prepare</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php
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
    <!-- </script> -->

    <script>
        // JavaScript to toggle payment sections
        document.addEventListener('DOMContentLoaded', function() {
            // Get the radio buttons
            const cashRadio = document.getElementById('cash');
            const onlineRadio = document.getElementById('online');

            // Get the payment sections
            const cashSection = document.getElementById('cash-section');
            const onlineSection = document.getElementById('online-section');

            // Function to toggle the payment sections based on the selected payment method
            function togglePaymentSections() {
                if (cashRadio.checked) {
                    cashSection.style.display = 'block'; // Show cash section
                    onlineSection.style.display = 'none'; // Hide online section
                } else if (onlineRadio.checked) {
                    cashSection.style.display = 'none'; // Hide cash section
                    onlineSection.style.display = 'block'; // Show online section
                }
            }

            // Add event listeners to the radio buttons to toggle the sections on change
            cashRadio.addEventListener('change', togglePaymentSections);
            onlineRadio.addEventListener('change', togglePaymentSections);

            // Initial check to show the correct section if pre-selected
            togglePaymentSections();
        });
    </script>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.all.min.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgYKHZB_QKKLWfIRaYPCadza3nhTAbv7c"></script>
    <script src="js/plugins.min.js"></script>
    <script src="js/script.min.js"></script>
    </body>


    </html>