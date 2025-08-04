<?php
include ('sidebar.php');

if (isset($_POST['remove_item'])) {
    $otc_id = $_POST['otc_id'];

    $sql = "UPDATE otc_item SET otc_void = 1 WHERE otc_id = ?";
    $result = $conn->prepare($sql);
    $result->bind_param("i", $otc_id);
    if ($result->execute()) {
        echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: "item removed",
            text: "list of order updated.",
            icon: "info"
        });
    });
</script>';

    }


}

if (isset($_POST['placeorder'])) {
    $order_total = $_POST['total'];
    $total_name = 'total';
    $total_id = 1;

    $order_numbers = rand(111, 999);
    $sql_check_number = "SELECT COUNT(over_number) as numbers FROM over_orders WHERE over_number = ?";
    $stmt_check = $conn->prepare($sql_check_number);
    $stmt_check->bind_param("s", $order_numbers);
    $stmt_check->execute();
    $stmt_check->bind_result($existing_number);
    $stmt_check->fetch();
    $stmt_check->close();


    if ($existing_number > 0) {
        $update_number = "UPDATE over_orders SET over_number = 0 WHERE order_number = '$existing_number'";
        $update_res = $conn->query($update_number);
        $order_numbers = rand(111, 999);
    }

    $sql_order = "INSERT INTO over_orders (employee_id, over_date, over_number) VALUES (?, NOW(), ?)";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("is", $id, $order_numbers);

    if ($stmt_order->execute()) {
        $over_id = $stmt_order->insert_id;

        foreach ($_POST['item_id'] as $key => $item_id) {
            $item_qty = $_POST['order_qty'][$key];
            $item_name = $_POST['order_name'][$key];
            $item_price = $_POST['price'][$key];

            $item_id = intval($item_id);
            $item_qty = intval($item_qty);
            $item_name = $conn->real_escape_string($item_name);

            $sql_items = "INSERT INTO over_items (over_id, item_id, over_name, over_price, over_qty) VALUES (?, ?, ?, ?, ?)";
            $stmt_items = $conn->prepare($sql_items);
            $stmt_items->bind_param("iissi", $over_id, $item_id, $item_name, $item_price, $item_qty);

            if (!$stmt_items->execute()) {
                echo 'Order item insertion failed: ' . $conn->error;
                break;
            }
        }


        $sql_total = "INSERT INTO over_total (over_id, over_total, over_tname, over_tamt) VALUES (?, ?, ?, ?)";
        $stmt_total = $conn->prepare($sql_total);
        $stmt_total->bind_param("issi", $over_id, $total_id, $total_name, $order_total);

        if ($stmt_total->execute()) {
            $update = "UPDATE otc o
            INNER JOIN otc_items oi ON oi.otc_id = o.otc_id
            SET o.otc_status = 1, oi.otc_order = 0
            WHERE o.employee_id = $id";
            $result_up = $conn->query($update);
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            title: "Order Sent.",
                            text: "Nice!",
                            icon: "success"
                        });
                        setTimeout(function() {
                            window.location.href = "orders.php";
                        }, 2000);
                    });
                </script>';
        } else {
            echo 'Order total insertion failed: ' . $conn->error;
        }
    } else {
        echo 'Order insertion failed: ' . $conn->error;
    }
}


?>

<div class="contents">

    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <h4 class="text-capitalize breadcrumb-title">Orders</h4>
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Order List</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center my-md-4">
                <div class="col-lg-12 justify-content-center">
                    <form method="POST">
                        <div class="card">
                            <div class="card-header px-md-4 text-dark text-capitalize bg-gray text-white">
                                <thead>
                                    <tr>

                                        <td>
                                            <span> Order Name</span>
                                        </td>
                                        <td>
                                            <span>Quantity</span>
                                        </td>
                                        <td class="text-center">
                                            <span>Item price</span>
                                        </td>

                                        <td>
                                            <span>Action</span>
                                        </td>
                                    </tr>
                                </thead>
                            </div>
                            <tbody>

                                <?php


                                $sql = "SELECT * FROM otc o LEFT JOIN otc_item oi ON oi.otc_id = o.otc_id WHERE o.employee_id = '$id' AND o.otc_status = 0 AND oi.otc_void = 0 AND oi.otc_order = 0";
                                $result = $conn->query($sql);
                                $total = 0;
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $total += $row["item_price"];
                                        echo '<div class="card-body d-flex justify-content-between align-items-center px-md-4">';
                                        echo '<tr>';
                                        echo '<input type="hidden" value="' . $row['otc_id'] . '" name="item_id[]">';
                                        echo '<td><div class=""><input value="' . $row['item_name'] . $row['item_desc'] . '" name="order_name[]" class="border-0 w-100" readonly></div></td>';
                                        echo '<td>
                                        <div class="d-flex">
                                            <input value="' . $row['otc_qty'] . '" type="number" class="border text-center quantity_input mx-sm-2" name="order_qty[]" id="qty_' . $row['otc_id'] . '" min="1" max="50" data-price="' . $row['item_price'] . '">
                                            <i class="w-25 px-sm-2 py-sm-2 border border-lighten btn uil uil-plus add-qty-btn" data-otc-id="' . $row['otc_id'] . '" ></i>
                                        </div>
                                    </td>';
                                        echo '<td><div><input value="' . $row['item_price'] . '" type="number" class="border-0 text-center price_input" name="price[]" readonly></div></td>';
                                        echo '<td><div>';

                                        echo '<form method="POST">';
                                        echo '<input type="hidden" name="otc_id" value="' . $row['otc_id'] . '">';
                                        echo '<button type="submit" class="btn btn-square" name="remove_item"><i class="uil uil-trash text-danger"></i></button>';
                                        echo '</form>';

                                        echo '</div></td>';
                                        echo '</tr>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo '<div class="card-body d-flex justify-content-center">
                                            <span>Your cart is empty.</span>
                                            </div>
                                            </div>';
                                }
                                ?>
                            </tbody>
                            <div class="card-footer rounded-bottom ">
                                <div class="d-flex justify-content-evenly">
                                    <div>
                                        <h5>Total:
                                        </h5>
                                    </div>
                                    <div>
                                        <input id="total_price" value="<?php echo $total > 0 ? $total : 0; ?>"
                                            class="row_total border-0 text-center" name="total" readonly>

                                    </div>
                                </div>
                                <?php
                                if ($total == 0) {
                                    echo ' <div class="my-md-2 text-center d-flex justify-content-center">
                  <button type="submit" name="placeorder" class="btn btn-square btn-warning w-25" disabled >Place
                    Order</button>
                </div>';
                                } else {
                                    echo ' <div class="my-md-2 text-center d-flex justify-content-center ">
                    <button type="submit" name="placeorder" class="btn btn-square btn-warning w-25">Place
                      Order</button>
                  </div>';
                                }
                                ?>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class=" footer-wrapper">
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

<script src="./js/sweetalert2.all.min.js"></script>
<script src="js/plugins.min.js"></script>
<script src="js/script.min.js"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>





<script>
    const quantityInputs = document.querySelectorAll('.quantity_input');
    const totalElement = document.getElementById('total_price');

    quantityInputs.forEach(input => {
        input.addEventListener('change', () => {
            calculateTotal();
        });
    });

    function calculateTotal() {
        let total = 0;
        quantityInputs.forEach(input => {
            const quantity = parseInt(input.value);
            const price = parseFloat(input.dataset.price);
            total += quantity * price;
        });
        totalElement.value = total.toLocaleString();
    }

    document.querySelectorAll('.add-qty-btn').forEach(function (icon) {
        icon.addEventListener('click', function () {
            var otcId = this.getAttribute('data-otc-id');
            var input = document.getElementById('qty_' + otcId);
            var currentQty = parseInt(input.value);

            var newQty = currentQty + 1;

            input.value = newQty;

            calculateTotal();
        });
    });

</script>





<script>
    $((function () {
        $(".adv-table1").footable({
            filtering: {
                enabled: !0
            },
            paging: {
                enabled: !0,
                current: 1
            },
            strings: {
                enabled: !1
            },
            filtering: {
                enabled: !0
            },
            components: {
                filtering: FooTable.MyFiltering
            }
        })
    })),
        FooTable.MyFiltering = FooTable.Filtering.extend({
            construct: function (t) {
                this._super(t);
                this.jobTitles = ["Active", "Pending", "Rejected"];
                this.jobTitleDefault = "All";
                this.$jobTitle = null;
            },
            $create: function () {
                this._super();
                var t = this,
                    s = $("<div/>", {
                        class: "form-group dm-select d-flex align-items-center adv-table-searchs__status my-xl-25 my-15 mb-0 me-sm-30 me-0"
                    }).append($("<label/>", {
                        class: "d-flex align-items-center mb-sm-0 mb-2",
                        text: "Status"
                    })).prependTo(t.$form);
                t.$jobTitle = $("<select/>", {
                    class: "form-control ms-sm-10 ms-0"
                }).on("change", {
                    self: t
                }, t._onJobTitleDropdownChanged).append($("<option/>", {
                    text: t.jobTitleDefault
                })).appendTo(s);
                $.each(t.jobTitles, (function (e, s) {
                    t.$jobTitle.append($("<option/>").text(s));
                }));
            },
            _onJobTitleDropdownChanged: function (t) {
                var e = t.data.self,
                    s = $(this).val();
                s !== e.jobTitleDefault ? e.addFilter("status", s, ["status"]) : e.removeFilter("status");
                e.filter();
            },
            draw: function () {
                this._super();
                var e = this.find("status");
                e instanceof FooTable.Filter ? this.$jobTitle.val(e.query.val()) : this.$jobTitle.val(this.jobTitleDefault);
            }
        });
</script>



</body>


</html>