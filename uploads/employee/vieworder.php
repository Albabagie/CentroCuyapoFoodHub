<?php
include ('sidebar.php');

$order_id = $_GET['order_id'];

$number = $conn->query("SELECT order_number FROM orders WHERE order_id = '$order_id'");
if ($number) {
    $numbers = $number->fetch_assoc()['order_number'];
} else {
    echo "Error: " . $conn->error;
}

if (isset($_POST['prepareorder'])) {
    $order_id = $_POST['order_id'];
    if (!empty($order_id)) {
        $order_update = "UPDATE orders SET status = 1 WHERE order_id = ?";
        $result_up = $conn->prepare($order_update);
        $result_up->bind_param("i", $order_id);
        if ($result_up->execute()) {
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

?>

<div class="contents">
    <div class="crm demo6 mb-25">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
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
                <!--  -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb-main user-member justify-content-sm-between ">
                            <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
                                <div
                                    class="d-flex align-items-center user-member__title justify-content-center me-sm-25">
                                    <h2 class="text-capitalize fw-500 breadcrumb-title">Orders</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!--  -->

                <?php
                $sql = "SELECT * FROM orders o 
                LEFT JOIN order_items oi ON oi.order_id = o.order_id 
                LEFT JOIN order_total ot ON oi.order_id = ot.order_id 
                WHERE o.order_id = '$order_id' AND status = 0 
                ORDER BY o.order_number, oi.item_id";

                $result = $conn->query($sql);
                $orders = array();

                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $order_number = $row['order_number'];
                        $order_id = $row['order_id'];

                        if (!isset($orders[$order_number])) {
                            $orders[$order_number] = array(
                                'order_number' => $order_number,
                                'items' => array(),
                                'order_id' => $order_id,
                                'total' => 0
                            );
                        }

                        $order_total = $row['item_price'] * $row['item_qty'];
                        $orders[$order_number]['total'] += $order_total;

                        $orders[$order_number]['items'][] = array(
                            'name' => $row['item_name'],
                            'quantity' => $row['item_qty'],
                            'price' => $row['item_price'],
                            'total' => number_format($order_total, 2)
                        );
                    }
                }

                foreach ($orders as $order) {
                    echo '<div class="d-flex justify-content-center">
                        <div class="card col-lg-8 px-lg-3 text-center receipt">
                            <div class="d-flex justify-content-end">
                                <div class="text-white p-2 bg-primary rounded w-25 mt-2">
                                    <span class="text-white">Order Number</span>
                                    <h4 class="text-white">' . $order['order_number'] . '</h4>
                                </div>
                            </div>
                            <form method="POST">
                                <input type="hidden" value="' . $order['order_id'] . '" name="order_id">
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
                                    <tbody class="">';

                    foreach ($order['items'] as $item) {
                        echo '<tr>
                                <td><input type="text" class="text-lowercase text-center border-0 w-100" value="' . $item['name'] . '" readonly></td>
                                <td><input type="number" class="text-center border-0 w-25" value="' . $item['quantity'] . '" readonly></td>
                                <td>' . $item['price'] . '</td>
                                <td>' . $item['total'] . '</td>
                            </tr>';
                    }

                    echo '</tbody>
                                </table>
                                <div class="w-100 d-flex justify-content-sm-around text-end">
                                    <div class="col-md-3">
                                        <h4>Total:</h4>
                                    </div>
                                    <div class="col-lg-6">
                                        <p><input class="border-0 text-center col-lg-4" type="number" name="total" value="' . number_format($order['total'], 2) . '" readonly></p>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end border-0 mt-lg-5">
                                    <button type="submit" name="prepareorder" class="btn btn-primary btn-square w-50 px-md-2 my-md-2 fs-6">Prepare Order</button>
                                </div>
                            </form>
                        </div>
                    </div>';
                }




                ?>

                <!--  -->
            </div>
        </div>
    </div>
    <footer class="footer-wrapper">
        <div class="footer-wrapper__inside">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="footer-copyright">
                            <p><span>© 2025</span><a href="#">Food Hub</a>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="footer-menu text-end">
                            <ul>
                                <li>
                                    <a href="#">About</a>
                                </li>
                                <li>
                                    <a href="#">Team</a>
                                </li>
                                <li>
                                    <a href="#">Contact</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
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
    </body>


    </html>