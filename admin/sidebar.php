<?php include('session.php') ?>

<?php


?>
<!doctype html>
<html lang="en" dir="ltr">



<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Food Hub | Management</title>
  <link rel="shortcut icon" href="../up/cuyapo/logo_cuyapo.jpg" type="">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/plugin.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="./css/group.css">
  <link rel="stylesheet" href="./css/customize.css">
  <!-- <link rel="stylesheet" href="./css/sweetalert2.min.css"> -->
  <link rel="stylesheet" href="unicons.iconscout.com/release/v4.0.0/css/line.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="./js/jquery.min.js"></script>

</head>
<style>


</style>

<body class="layout-light side-menu">
  <div class="mobile-author-actions" style="background:rgb(19, 39, 67);"></div>
  <header class="header-top">
    <nav class="navbar" style="background:rgb(19, 39, 67);">

      <div class="navbar-left">
        <div class="mx-md-3">
          <a href="#" class="sidebar-toggle">
            <i class="uil uil-bars text-lighten"></i>
          </a>
        </div>
      </div>
      <?php


      $item_low =  "SELECT *, COUNT(*) AS list_low FROM inventory WHERE state = 0 AND product_qty <= 50";
      $item_result = $conn->query($item_low);
      if ($item_result->num_rows > 0):
        while ($row = $item_result->fetch_assoc()):
          $low_qty = $row['list_low'];
      ?>
          <div class="navbar-right">
            <ul class="navbar-right__menu">
              <li class="nav-notification">
                <div class="dropdown-custom">
                  <a href="javascript:;" class="nav-item-toggle position-relative py-1">
                    <i class="uil uil-bell text-white fs-4 position-relative">
                      <span class="position-absolute top-0 start-0 px-2">
                        <?php
                        if ($low_qty == 0) {
                          echo '';
                        } else {
                          echo '<span class="badge-circle badge-warning mb-15">' . $low_qty . '</span>';
                        }
                        ?>

                      </span>
                    </i>
                  </a>

                  <div class="dropdown-parent-wrapper">
                    <div class="dropdown-wrapper">
                      <h2 class="dropdown-wrapper__title">Item Low Quantity <span class="text-danger mx-2" style="font-size:12px">*at leat 50</span></h2>
                      <ul>
                        <?php
                        $items_added = "SELECT *, i.*, 
                                      (SELECT SUM(item_qty) 
                                       FROM order_items oi 
                                       WHERE oi.product_id = i.product_id) AS name_count 
                               FROM inventory i 
                               WHERE i.state = 0 AND i.product_qty <= 50";
                        $item_inventory = $conn->query($items_added);

                        if ($item_inventory) {
                          while ($row = $item_inventory->fetch_assoc()) {
                            $product_name = $row['product_name'];
                            $inv_product_qty = $row['product_qty'];
                            $oi_qty = $row['name_count'];
                            $product_id = $row['product_id'];

                            // Query to get the over_qty from the over_items table
                            $over_qty_query = "SELECT SUM(over_qty) AS over_qty 
                                          FROM over_items 
                                          WHERE over_name = '$product_name' 
                                          OR item_id = '$product_id'";
                            $over_qty_result = $conn->query($over_qty_query);

                            if ($over_qty_result) {
                              $over_qty_row = $over_qty_result->fetch_assoc();
                              $over_qty = $over_qty_row['over_qty'] ?? 0;

                              $oi_qty += $over_qty;
                            }

                            $item_left = $inv_product_qty - $oi_qty;


                            if ($item_left <= 50) {
                              echo '<a href="inventory">
                                        <li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
                                            <div class="nav-notification__type nav-notification__type--primary">
                                              <i class="las la-envelope-open"></i>
                                            </div>
                                            <div class="nav-notification__details">
                                              <p class="text-dark">
                                              <span class=" rounded px-1 py-1 text-white bg-danger mx-2">' . $row['product_name'] . '</span>Low stock [ ' . $row['product_qty'] . ' ]
                                              </p>
                                            </div>
                                          </li>
                                          </a>';
                            } else {
                              echo '<li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
                                            <div class="nav-notification__type nav-notification__type--primary">
                                              <i class="las la-envelope-open"></i>
                                            </div>
                                            <div class="nav-notification__details">
                                              <p class="text-dark">
                                                No Low Stock Item.
                                              </p>
                                            </div>
                                          </li>';
                            }
                          }
                        }
                        ?>

                      </ul>
                    </div>
                  </div>

                <?php endwhile; ?>
              <?php endif; ?>
                </div>
              </li>

              <li class="nav-author">

              </li>
              <li class="nav-author">
                <div class="dropdown-custom">
                  <a href="javascript:;" class="nav-item-toggle"><img src="img/user.png" alt class="rounded-circle">
                    <span class="nav-item__title text-white"><?php echo  $admin_name  ?><i class="las la-angle-down nav-item__arrow"></i></span>
                  </a>
                  <div class="dropdown-parent-wrapper">
                    <div class="dropdown-wrapper">
                      <div class="nav-author__info">
                        <div class="author-img">
                          <img src="img/user.png" alt class="rounded-circle">
                        </div>
                        <div>
                          <h6><?php echo $admin_name  ?></h6>
                        </div>
                      </div>
                      <div class="nav-author__options">
                        <ul>
                          <li>
                            <a href="profile">
                              <i class="uil uil-user"></i> Profile
                            </a>
                          </li>
                          <li>
                            <a href="wallet">
                              <i class="uil uil-wallet"></i> Set up Wallet
                            </a>
                          </li>
                        </ul>
                        <a href="../login/logout.php" class="nav-author__signout">
                          <i class="uil uil-sign-out-alt"></i> Sign Out</a>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
            <div class="navbar-right__mobileAction d-md-none">
              <!-- <a href="#" class="btn-search">

            <img src="img/svg/x.svg" alt="x" class="svg feather-x"></a> -->
              <a href="#" class="btn-author-action">
                <img class="svg" src="img/svg/more-vertical.svg" alt="">

              </a>

            </div>
          </div>
    </nav>
  </header>
  <main class="main-content">
    <div class="sidebar-wrapper">
      <div class="sidebar sidebar-collapse" id="sidebar" style="background:rgb(19, 39, 67)">
        <div class="sidebar__menu-group">
          <ul class="sidebar_nav">


            <li>
              <a href="index.php" class>
                <span class="nav-icon uil uil-file-contract" style="font-size:18px;"></span>
                <span class="menu-text text-white" style="font-size:16px;">Dashboard</span>

              </a>
            </li>
            <li>
              <a href="stalls.php" class>
                <i class="nav-icon uil uil-create-dashboard " style="font-size:18px;"></i>
                <span class="menu-text text-white" style="font-size:16px;">Food Stalls</span>

              </a>
            </li>
            <li>
              <a href="inventory.php" class>
                <i class="nav-icon uil uil-home" style="font-size:18px;"></i>
                <span class="menu-text text-white" style="font-size:16px;">Inventory</span>

              </a>
            </li>
            <li>
              <a href="employee.php" class>
                <span class="nav-icon uil uil-users-alt " style="font-size:18px;"></span>
                <span class="menu-text text-white" style="font-size:16px;">Employee List</span>

              </a>
            </li>

          </ul>
        </div>
      </div>
    </div>