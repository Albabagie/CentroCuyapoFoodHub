<?php
include ('sidebar.php');



if (isset($_POST['submit_ratings'])) {

  echo "Form submitted!";
  var_dump($_POST);
  $counter = 1;
  while (isset($_POST['rating_' . $counter])) {
    $item_id = $_POST['item_id_' . $counter];
    $rating = $_POST['rating_' . $counter];
    $feedback = $_POST['feedback_' . $counter];
    $order_id = $_POST['order_id_' . $counter];
    $order_number = $_POST['order_number_' . $counter];

    $sql = "INSERT INTO rating (customer_id, item_id, ratings, review, order_number, order_id) VALUES ('$id','$item_id', '$rating', '$feedback','$order_number', '$order_id')";

    if ($conn->query($sql) === TRUE) {
      echo '<script>alert("okay daw");</script>';
    } else {
      echo '<script>alert("okay mali sya");</script>';
      echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
    }

    $counter++;
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
      <div class="row">
        <div class="col-md-12">
          <div class="card my-sm-4">
            <div class="card-body">
              <div class="userDatatable adv-table-table global-shadow border-light-0 w-100 adv-table">
                <div class="table-responsive">
                  <div class="adv-table-table__header">
                    <h4>My Orders</h4>
                  </div>
                  <div id="filter-form-container"></div>
                  <table class="table mb-0 table-borderless adv-table1" data-filter-container="#filter-form-container"
                    data-paging-current="1" data-paging-position="right" data-paging-size="10">
                    <thead>
                      <tr class="userDatatable-header">



                        <th data-type="html" data-name="status" class="text-center">
                          <span class="userDatatable-title">Order Number</span>
                        </th>
                        <th>
                          <span class="userDatatable-title ">Total Payment</span>
                        </th>
                        <th>
                          <span class="userDatatable-title ">Order Date</span>
                        </th>
                        <th>
                          <span class="userDatatable-title ">Status</span>
                        </th>
                        <th>
                          <span class="userDatatable-title ">Action</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php

                      $order_items = $conn->query("SELECT * FROM orders o LEFT JOIN order_total ot ON ot.order_id = ot.order_id WHERE o.customer_id = '$id' GROUP BY o.order_id");

                      if ($order_items->num_rows > 0) {
                        while ($row = $order_items->fetch_assoc()) {
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
                          if ($row['status'] == 2) {
                            echo '<span class="bg-gray text-white p-sm-1 rounded text-center">Out</span>';
                          } elseif($row['status'] == 1) {
                            echo '<span class="bg-success text-white p-sm-1 rounded text-center">ready</span>';
                          }else{
                            echo '<span class="bg-info text-white p-sm-1 rounded text-center">Preparing</span>';
                          }
                          echo '</td>';
                          echo '<td>';
                          $order_number = $row['order_number'];
                          $exist_rating = $conn->query("SELECT * FROM rating 
                          WHERE order_number = '$order_number'");


                          if ($exist_rating->num_rows > 0) {
                            if ($row['status'] == 2) {
                              echo '<button class="btn btn-light btn-square border border-lighten" data-bs-toggle="modal" data-bs-target="#view_order' . $row['order_number'] . '" disabled>Thank you! <i class="uil uil-heart text-danger" ></i></button>';
                            }

                          } elseif($row['status'] == 1) {
                           
                          echo  '<button class="btn btn-warning btn-square text-white" data-bs-toggle="modal" data-bs-target="#view_order'. $row['order_number'] . '"><i class="uil uil-feedback"></i>Write Review</button>';
                         } else{
                          echo '<button class="btn btn-primary btn-square text-white" data-bs-toggle="modal" data-bs-target="#order_item' . $row['order_number'] . '"><i class="uil uil-feedback">View Order</i></button>';
                         }
                          echo '</td>
        </tr>';

                          $order_number = $row['order_number'];
                          $order_date = $row['order_date'];
                          $order_items_result = $conn->query("SELECT *, SUM(item_price) as total FROM orders o LEFT JOIN order_items oi ON oi.order_id = o.order_id 
                           WHERE o.order_number = '$order_number'");
                        
                          echo '<div class="modal fade" id="view_order' . $row['order_number'] . '" tabindex="-1" aria-labelledby="view_order' . $row['order_number'] . 'Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="view_order' . $row['order_number'] . 'Label">We appreciate your feedback!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST">
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
                            echo '<input type="hidden" name="order_number_' . $counter . '" value="' . $row['order_number'] . '">';
                            echo '<input type="hidden" name="order_id_' . $counter . '" value="' . $row['order_id'] . '">';
                            echo '<input type="hidden" name="item_id_' . $counter . '" value="' . $item_row['product_id'] . '">';
                            echo '<input class="form-control w-100" type="text" id="chosenWordInput_' . $counter . '" name="feedback_' . $counter . '" placeholder="Feedback">';
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
                          if ($row['status'] != 2) {
                            echo '<button class="btn btn-gray mx-sm-2" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                  <button class="btn btn-warning mx-sm-2 text-white" disabled><i class="uil uil-star"></i>Give Rating</button>';
                          } else {
                            echo '<button class="btn-gray mx-sm-2" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" name="submit_ratings" class="btn btn-warning mx-sm-2" disabled><i class="uil uil-star"></i>Give Rating</button>';
                          }

                          echo '</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>';
                         

        echo '<div class="modal fade" id="order_item' . $row['order_number'] . '" tabindex="-1" aria-labelledby="view_order' . $row['order_number'] . 'Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="view_order' . $row['order_number'] . 'Label">Thank you for choosing us!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="new-member-modal">
                        <div class="form-group mb-20">
                            <h3 class="text-center">Your Orders</h3>
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
                          echo '<div class="card justify-content-between">
                       <div class="text-dark m-2 d-flex flex-col justify-content-between position-relative ">
                        <span class="col-md-6">'.$item_name.'</span>
                        <span class="col-md-2">'.$item_qty.'</span>
                        <span class="col-md-4 text-end">'.$item_price.'</span>
                        </div>
                          </div>';
                    
                 
                  echo '
                 
                  </div>
                            </div>
                            <div class="my-4 mx-4 d-flex flex-col justify-content-between">
                            <h3 class="text-dark">Total:</h3>
                            <span class="text-dark">'. $total .'</span>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">';
                          

                  echo '</div>
                </div>
            </form>
        </div>
    </div>
</div>';
                        }

                      } else {
                        echo '<tr class="col-lg-12">';
                        echo '<td colspan="5"><div class="w-100 d-flex justify-content-center">No Order Yet.</div></td>';
                        echo '</tr>';
                      }

                      ?>

        </div>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
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

  <script src="js/plugins.min.js"></script>
  <script src="js/script.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
            s = $("<div />", {
              class: "form-group dm-select d-flex align-items-center adv-table-searchs__status my-xl-25 my-15 mb-0 me-sm-30 me-0"
            }).append($("<label />", {
              class: "d-flex align-items-center mb-sm-0 mb-2",
              text: "Status"
            })).prependTo(t.$form);
          t.$jobTitle = $("<select />", {
            class: "form-control ms-sm-10 ms-0"
          }).on("change", {
            self: t
          }, t._onJobTitleDropdownChanged).append($("<option />", {
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



  </body >


  </html >