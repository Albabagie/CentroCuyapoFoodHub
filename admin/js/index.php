<?php
include('sidebar.php');

$orders = $conn->query("SELECT COUNT(*) AS order_count 
FROM orders 
WHERE status = 2 
AND MONTH(order_date) = MONTH(CURRENT_DATE())");
$order_count = $orders->fetch_assoc()['order_count'];


$sales_query = $conn->query('SELECT *, SUM(total_amt) as sales FROM order_total ot LEFT JOIN orders o ON o.order_id = ot.order_id WHERE MONTH(order_date) = MONTH(CURRENT_DATE()) AND YEAR(order_date) = YEAR(CURRENT_DATE())');
$sales_result = $sales_query->fetch_assoc();
$current_sales = $sales_result['sales'];

$query = "SELECT SUM(subquery.total) AS total_sum
FROM (
    SELECT ot.over_tamt AS total
    FROM over_total ot
    LEFT JOIN over_orders od ON od.over_id = ot.over_id
    WHERE MONTH(od.over_date) = MONTH(CURRENT_DATE())
      AND YEAR(od.over_date) = YEAR(CURRENT_DATE())

    UNION ALL

    SELECT ot.total_amt AS total
    FROM order_total ot
    LEFT JOIN orders o ON o.order_id = ot.order_id
    WHERE MONTH(o.order_date) = MONTH(CURRENT_DATE())
      AND YEAR(o.order_date) = YEAR(CURRENT_DATE())
) AS subquery";

$stmt = $conn->query($query);

// Fetch the result
$result = $stmt->fetch_assoc();

// Get the sum value from the result
$totalSum = $result['total_sum'];


$prev_sales_query = $conn->query('SELECT SUM(total_amt) as prev_sales FROM order_total ot LEFT JOIN orders o ON o.order_id = ot.order_id WHERE MONTH(order_date) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND YEAR(order_date) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))');
$prev_sales_result = $prev_sales_query->fetch_assoc();
$prev_sales = $prev_sales_result['prev_sales'];

$percentage_difference = 0; // Default value in case the calculation is skipped

if ($prev_sales != 0) {
  $percentage_difference = (($totalSum  - $prev_sales) / $prev_sales) * 100;
} elseif ($totalSum  != 0) {
  $percentage_difference = 100;
} else {
  // Both $current_sales and $prev_sales are zero, so no need to calculate
  $percentage_difference = 0;
}




$total_item_query = $conn->query("SELECT COUNT(*) as items FROM inventory WHERE state = 0");
$item_total = $total_item_query->fetch_assoc()['items'];


$promo_update = "SELECT * FROM promo WHERE promo_status = 'active'";
$result_update = $conn->query($promo_update);

if ($result_update->num_rows > 0) {
  $date_now = date('Y-m-d');
  while ($row = $result_update->fetch_assoc()) {
    $active_promo = $row['active_promo'] . '</br>';
    $update_auto = "UPDATE promo 
                 SET promo_status = 'not' 
                 WHERE active_promo <=  '$date_now'
                 AND promo_status = 'active'";
  }
}

?>
<div class="contents m-2" style="background:rgb(252,250,241)">
  <div class="container-fluid mb-4">
    <div class="social-dash-wrap">
      <div class="row">
        <div class="col-lg-12">
          <div class="breadcrumb-main ">
            <h4 class="text-capitalize breadcrumb-title">Dashboard</h4>
            <div class="breadcrumb-action justify-content-center flex-wrap">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">analytics</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
        <div class="col-xxl-4 col-sm-4 mb-25">
          <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between" style="box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;background-color: rgba(57, 158, 102, 0.49);">
            <div class="overview-content w-100">
              <div class=" ap-po-details-content d-flex flex-wrap justify-content-between" style="background-color: rgba(57, 158, 102, 0.52);">
                <div class="ap-po-details__titlebar">
                  <h5 class="text-white">Today's Order</h5>
                  <h1 class="text-white">
                    <?php echo $order_count ?>
                  </h1>
                </div>
                <div class="ap-po-details__icon-area">
                  <div class="svg-icon order-bg-opacity-primary color-primary">
                    <i class="uil uil-shopping-basket"></i>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>



        <div class="col-xxl-4 col-sm-4 mb-25">
          <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between" style="box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;background-color: rgba(206, 201, 0, 0.57);">
            <div class="overview-content w-100">
              <div class=" ap-po-details-content d-flex flex-wrap justify-content-between" style="background-color: rgba(206, 201, 0, 0.59); ">
                <div class="ap-po-details__titlebar">
                  <div>
                    <h5 class="text-white">Monthly Sales
                      <span class="d-none"></span>
                      <span id="percentage_diff" class="text-white badge-pill p-1 rounded" style="font-size:14px;margin-left:10px;box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;">
                        <?php
                        if ($percentage_difference < 0):
                          echo number_format($percentage_difference, 2) . ' % <i class="uil uil-arrow-down text-danger bounce-animation"></i>';
                        ?>
                          <span class="hover-text">lost profit</span>
                        <?php elseif ($percentage_difference == 0):
                          echo number_format($percentage_difference, 2) . '%';
                        ?>
                        <?php else:
                          echo number_format($percentage_difference, 2) . '% <i class="uil uil-arrow-up text-success bounce-animation"></i>';

                        ?>
                          <span class="hover-text">Profit Gain Increase</span>
                        <?php endif; ?>
                      </span>
                    </h5>
                  </div>
                  <h2 class="text-white">
                    <span class="text-white"><?php echo $totalSum; ?> </span>
                    <?php if ($totalSum > $prev_sales) {
                      echo '<span class="bounce-animation" style="margin-left:-10px"></span>';
                    } elseif ($totalSum == $prev_sales) {
                      echo '<span style="margin-left:-10px"></span>';
                    } else {
                      echo '<span class="bounce-animation" style="margin-left:-10px"></span></i></span>';
                    } ?>
                    <div class="text-white position-absolute mt-1" style="font-size:12px;">
                      <span class="text-dark"><?php echo number_format($prev_sales, 2) ?> <span class="text-gray">last month sales</span></span>
                    </div>
                  </h2>
                </div>
                <div class="ap-po-details__icon-area">
                  <div class="svg-icon order-bg-opacity-warning color-warning">
                    <i class="uil uil-analytics"></i>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
        <div class="col-xxl-4 col-sm-4 mb-25">
          <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between" style="box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;background-color: rgba(0, 156, 221, 0.59);">
            <div class="overview-content w-100">
              <div class=" ap-po-details-content d-flex flex-wrap justify-content-between" style="background-color: rgba(0, 156, 221, 0.6);">
                <div class="ap-po-details__titlebar">
                  <h5 class="text-white">
                    Total Items
                  </h5>
                  <div class="text-light position-absolute mt-1" style="font-size:12px;">
                    <h2 class="text-white">
                      <span class="text-white"><?php echo $item_total ?> <span class="text-white">Listed</span></span>
                    </h2>
                  </div>
                </div>
                <div class="ap-po-details__icon-area">
                  <div class="svg-icon order-bg-opacity-warning color-warning">
                    <i class="uil uil-pen"></i>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <br>
      <br>
      <br>
      <div class="container-fluid">
        <div class="social-dash-wrap">
          <div class="row">
            <div class="col-lg-12">
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card p-4" style="box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;">
                <div class="card-body">
                  <div class="row">
                    <div class="mb-4 d-flex flex-row justify-content-between">
                      <div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="card head-list shadow-sm">
                        <div class="card-header banner-list text-white text-center" style="background:rgb(152,204,175)">
                          <h3 class="text-white">Favorite Products</h3>
                        </div>
                        <div class="card text-center rounded-0 gap-2 px-2 py-2">
                          <?php
                          $top_choice = 'SELECT *, COUNT(item_name) as item FROM order_items GROUP BY item_name ORDER BY item DESC LIMIT 3';
                          $top = $conn->query($top_choice);

                          if ($top->num_rows > 0) {
                            $counter = 0;

                            while ($rows = $top->fetch_assoc()) {

                              $rating_review = $conn->query("SELECT *, AVG(ratings) AS r_avg, COUNT(review) AS reviews FROM rating WHERE item_id = " . $rows['product_id']);
                              $result_review = $rating_review->fetch_assoc();
                              $reviews = $result_review['reviews'];
                              $ratings = $result_review['r_avg'];
                              $counter++;
                              $rankClass = '';
                              if ($counter == 1) {
                                $rankClass = "first-place";
                              } elseif ($counter == 2) {
                                $rankClass = "second-place";
                              } elseif ($counter == 3) {
                                $rankClass = "third-place";
                              }
                              echo "<a href='viewitem?product_id=" . $rows['product_id'] . "'>
                    <div class='card-body favorite-item d-flex justify-content-center align-items-center  $rankClass'>
                        <div class='counter-badge text-white mr-2'>" . $counter . "</div>
                        <div class='product-info'>
                            <span class='product-name d-block'>" . $rows['item_name'] . "</span>
                            <span class='product-price d-block text-light' style='font-size:16px'><span class='text-decoration-underline'>Php: " . $rows['item_price'] . "</span>
                            <span class='mx-3 text-light' style='font-size:14px'>" . $rows['item'] . "<i class='uil uil-user text-light mx-2'>  order count</i></span>
                            </span>
                        </div>
                    </div>
                    </a>";
                            }
                          } else {
                            echo '<div class="card-body">No data.</div>';
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <!-- <span class=' text-light' style='font-size:14px'>".  number_format($ratings,1) ."<i class='uil uil-star text-warning'></i></span> -->
                    <!-- <span class='mx-4 text-light' style='font-size:14px'>". $reviews." reviews</span> -->


                    <div class="col-lg-6 mb-4">
                      <div class="card box-rating shadow-sm">
                        <div class="card-header head-rating bg-primary text-white text-center">
                          <h3 class="text-white">Top Rated Products</h3>
                        </div>
                        <div class="card-body p-2 text-center">
                          <?php
                          $rated = "SELECT *, AVG(ratings) AS ratings FROM rating r JOIN order_items oi ON oi.product_id = r.item_id GROUP BY item_id ORDER BY ratings DESC LIMIT 3";
                          $result_rated = $conn->query($rated);
                          $ranking = 0;
                          ?>
                          <?php if ($result_rated->num_rows > 0): ?>
                            <?php while ($row_rated = $result_rated->fetch_assoc()):
                              $ranking++;
                              $ranking_class = '';
                              if ($ranking == 1) {
                                $ranking_class = 'top';
                              } elseif ($ranking == 2) {
                                $ranking_class = 'behind';
                              } elseif ($ranking == 3) {
                                $ranking_class = 'last';
                              }
                            ?>
                              <div class="my-3 ranking-item <?php echo $ranking_class; ?>">
                                <div class="ranking-badge text-white py-2 px-3">
                                  <span class="ranking-number"><?php echo $ranking; ?></span>
                                </div>
                                <div class="ranking-details px-3 py-2">
                                  <span class="product-name"><?php echo $row_rated['item_name']; ?></span>
                                  <span class="product-rating"><?php echo number_format($row_rated['ratings'], 1); ?><i class="uil uil-star text-warning"></i></span>
                                </div>
                              </div>
                            <?php endwhile; ?>
                          <?php else: ?>
                            <div class="card-body">No data.</div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>


                  </div>
                </div>
              </div>
            </div>



          </div>
        </div>
      </div>



      <br>
      <div class="container-fluid">
        <di class="social-dash-wrap">
          <div class="row">
            <div class="col-lg-12">

            </div>
          </div>
          <div class="row mb-25">
            <div class="col-md-12">
              <div class="card" style="box-shadow: rgba(0, 0, 0, 0.15) 2.4px 2.4px 3.2px;">
                <div class="card-body">
                  <div class="row">
                    <div class="mb-4 d-flex flex-row justify-content-between">

                      <div>


                      </div>
                    </div>
                    <div class="col-lg-12 mb-4">
                      <div class="card" style="box-shadow: -8px 4px 10px 1px rgba(221, 227, 43, 0.42),
            13px -12px 4px -8px rgba(158, 158, 158, 0.62);">

                        <div class="card-header">
                          <h3> Sales Performance</h3>
                          <form action="edit_data" method="POST">
                            <button type="submit" class="btn btn-success" name="update_data_sales">Refresh</button>
                          </form>
                        </div>

                        <div id="chart" width="800" height="400">
                          <div id="nodata" class="card-body text-center"></div>

                        </div>



                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>



          </div>

          <div class="row my-20">
            <div class="col-lg-12">
              <div class="card px-15">
                <div class="card-header">
                  <h2>Order List</h2>
                  <a href="data_excel" class="btn btn-primary">
                    <i class="uil uil-download-alt"></i> Download List
                  </a>
                </div>
                <div id="filter-form-container">
                </div>
                <table class="table table-bordered border border-light-gray adv-table1" data-filter-container="#filter-form-container" data-paging-current="1" data-paging-position="right" data-paging-size="10">
                  <thead class="text-center">
                    <tr>
                      <th>No. #</th>
                      <th>Item Order Count</th>
                      <th data-type="html" data-name="type">Order type</th>
                      <th>Order Date</th>
                      <th>Order total</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $list_of = "SELECT 
                          (SELECT COUNT(over_name) FROM over_items WHERE over_id = od.over_id) AS item_total, 
                          od.over_id, 
                          od.over_date AS date,
                          od.over_status as status,
                          ot.over_tamt AS total
                      FROM over_total ot
                      LEFT JOIN over_orders od ON od.over_id = ot.over_id
                      UNION
                      SELECT 
                          (SELECT COUNT(item_name) FROM order_items WHERE order_id = o.order_id) AS item_total, 
                          o.order_id, 
                          o.order_date AS date,
                          o.status as status,
                          ot.total_amt AS total
                      FROM order_total ot
                      LEFT JOIN orders o ON o.order_id = ot.order_id 
                      ORDER BY date DESC";

                    $result_of = $conn->query($list_of);
                    if ($result_of->num_rows > 0) {
                      $counter = 1;
                      while ($row_of = $result_of->fetch_assoc()) {
                        $list_id = $row_of['over_id'];
                        $sql = "SELECT * FROM over_orders  WHERE over_id = '$list_id'";
                        $result_sql = $conn->query($sql);
                        if ($result_sql && $result_sql->num_rows > 0) {
                          $rows = $result_sql->fetch_assoc();
                        } else {
                          $rows = null;
                        }
                    ?>
                        <tr>
                          <td><?php echo $counter ?></td>
                          <td class="text-center"><?php echo $row_of['item_total'] ?> item listed</td>
                          <td class="text-center"><?php
                                                  if ($rows && $row_of['over_id'] == $rows['over_id']) {
                                                    echo "Counter";
                                                  } else {
                                                    echo "App Order";
                                                  }
                                                  ?></td>
                          <td class="text-center"><?php echo $row_of['date'] ?></td>
                          <td class="text-center"><?php echo $row_of['total'] ?></td>
                          <td class="text-center"><?php echo $row_of['status'] == 2 ? 'PAID' : 'PREPARING' ?></td>
                        </tr>
                    <?php
                        $counter++;
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!--  -->
      </div>
    </div>



    <br>

  </div>
</div>
</div>
</div>



</div>
</div>
</div>



</main>
<div id="overlayer">
  <div class="loader-overlay">
    <div class="dm-spin-dots spin-lg">
      <span class="spin-dot badge-dot dot-primary"></span>
      <span class="spin-dot badge-dot dot-danger"></span>
      <span class="spin-dot badge-dot dot-info"></span>
      <span class="spin-dot badge-dot dot-warning"></span>
    </div>

  </div>
</div>
<script src="js/apexcharts.js"></script>

<script src="js/plugins.min.js"></script>
<script src="js/script.min.js"></script>

<!-- <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    fetch('forecast.php')
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          // console.error(data.error);
          return data;
        }

        // Check if all sales values are zero
        const salesValues = [
          data.two_months.sales,
          data.three_months.sales,
          data.four_months.sales,
          data.previous_month.sales,
          data.current_month.sales,
          Math.round(data.forecast_sales)
        ];

        const allZero = salesValues.every(value => value == 0);

        if (allZero) {
          document.querySelector("#nodata").innerHTML = 'Currently no data found';
          return;
        }

        var options = {
          series: [{
            name: "Sales",
            data: salesValues
          }],
          chart: {
            height: 350,
            type: 'line',
            zoom: {
              enabled: false
            },
            toolbar: {
              show: false
            }
          },
          dataLabels: {
            enabled: true
          },
          stroke: {
            curve: 'smooth'
          },
          title: {
            text: '',
            align: 'center'
          },
          grid: {
            row: {
              colors: ['#f3f3f3', 'transparent'],
              opacity: 0.5
            },
          },
          xaxis: {
            categories: [
              data.two_months.month,
              data.three_months.month,
              data.four_months.month,
              data.previous_month.month,
              data.current_month.month,
              'Forecast'
            ],
          }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
      })
      .catch(error => console.error('Error fetching data:', error));
  });
</script>
<script>
  $((function() {
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
      construct: function(t) {
        this._super(t);
        this.jobTitles = ["App Order", "Counter"];
        this.jobTitleDefault = "All";
        this.$jobTitle = null;
      },
      $create: function() {
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
        $.each(t.jobTitles, (function(e, s) {
          t.$jobTitle.append($("<option/>").text(s));
        }));
      },
      _onJobTitleDropdownChanged: function(t) {
        var e = t.data.self,
          s = $(this).val();
        s !== e.jobTitleDefault ? e.addFilter("type", s, ["type"]) : e.removeFilter("type");
        e.filter();
      },
      draw: function() {
        this._super();
        var e = this.find("type");
        e instanceof FooTable.Filter ? this.$jobTitle.val(e.query.val()) : this.$jobTitle.val(this.jobTitleDefault);
      }
    });
</script>



</body>


</html>