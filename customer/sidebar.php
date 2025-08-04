<?php include('session.php');

?>
<!doctype html>
<html lang="en" dir="ltr">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Food Hub | Customer</title>
  <link rel="shortcut icon" href="../up/cuyapo/logo_cuyapo.jpg" type="">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="css/plugin.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="./css/sweetalert2.min.css">
  <link rel="stylesheet" href="unicons.iconscout.com/release/v4.0.0/css/line.css">
  <link rel="stylesheet" href="./css/customize.css">
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
</head>
<style>
  .rating {
    display: inline-flex;
    flex-direction: row-reverse;
    justify-content: center;
    align-items: flex-start;
  }

  .rating input {
    display: none;
  }

  .rating label {
    cursor: pointer;
    width: 40px;
    height: 40px;
    margin: 0;
    padding: 0;
    font-size: 30px;
  }

  .rating label:before {
    content: '\2605';
    display: block;
    color: #ccc;
    font-size: 30px;
  }

  .rating input:checked~label:before {
    color: #fdd835;
  }

  .rating label:hover:before,
  .rating label:hover~label:before {
    color: #ffeb3b;
  }

  .swal2-top {
    z-index: 2000 !important;
  }

  @media (max-width: 768px) {
    .s-w-80 {
      width: 85% !important;
    }
  }

  .card.h-100 {
    display: flex;
    flex-direction: column;
  }

  .card-body {
    flex: 1 1 auto;
  }
</style>

<body class="layout-light side-menu">
  <div class="mobile-search">
    <form action="http://demo.dashboardmarket.com/" class="search-form">
      <img src="img/svg/search.svg" alt="search" class="svg">
      <input class="form-control me-sm-2 box-shadow-none" type="search" placeholder="Search..." aria-label="Search">
    </form>
  </div>
  <div class="mobile-author-actions" style="background:rgb(35, 35, 35);"></div>
  <header class="header-top">
    <nav class="navbar" style="background:rgb(35, 35, 35)">
      <div class="navbar-left">
        <div class="" >

          <a href="#" class="sidebar-toggle mx-2">
            <i class="uil uil-bars text-light"></i>
          </a>
        </div>

      </div>
      <div class="navbar-right">
        <ul class="navbar-right__menu">
          <li>
            <!-- <a href="orders" class="position-relative"> -->
              <a href="orders" class="position-relative d-inline-block">
    <i class="nav-icon uil uil-shopping-basket fs-5 text-white"></i>
             <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                  <?php
                  $orders_on_time = "SELECT * FROM cart
                                      WHERE MONTH(order_date) = MONTH(CURRENT_DATE)
                                      AND YEAR(order_date) = YEAR(CURRENT_DATE)
                                      AND customer_id = $id
                                      AND cart_status = 0";
                  $order_count = $conn->query($orders_on_time);

                  $order_on_time = $order_count->num_rows;
                  if ($order_on_time > 0) {
                    echo '<span class=" w-75 h-75 badge-circle badge-warning ms-1">' . $order_on_time . '</span>';
                  } ?>
                </span>
              <!-- </i> -->
            </a>
          </li>
          <?php
          $order_notif = "SELECT * FROM orders
              WHERE MONTH(order_date) = MONTH(CURRENT_DATE)
              AND YEAR(order_date) = YEAR(CURRENT_DATE)
              AND customer_id = $id
              AND status = 2";

          $notif_res = $conn->query($order_notif);

          $notif_res_count = $notif_res->num_rows;
          ?>
          <li class="nav-notification">
            <div class="dropdown-custom">
              <a href="javascript:;" class="nav-item-toggle position-relative py-1">
                <!-- <img class="svg" src="img/svg/alarm.svg" alt="img"> -->
                <i class="uil uil-bell fs-5 text-white"></i>

                <span class="position-absolute top-0 start-0">
                  <?php
                  if ($notif_res_count > 0) {
                    echo '<span class=" w-75 h-75 badge-circle badge-warning ms-1">' . $notif_res_count . '</span>';
                  } ?>
                </span>
              </a>
              <?php
              $sql = "SELECT * FROM orders
              WHERE MONTH(order_date) = MONTH(CURRENT_DATE)
              AND YEAR(order_date) = YEAR(CURRENT_DATE)
              AND customer_id = $id
              AND status = 2";
              $result = $conn->query($sql);

              $notificationCount = $result->num_rows;


              echo '<div class="dropdown-parent-wrapper">
        <div class="dropdown-wrapper">
          <h2 class="dropdown-wrapper__title">Notifications ';

              if ($notificationCount > 0) {
                echo '<span class="badge-circle badge-warning ms-1">' . $notificationCount . '</span>';
              }

              echo '</h2><ul>';

              if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {

                  echo '
                        <form>
                        <a href="orderhistory?order_id=' . $row['order_id'] . '">
                        
                        <li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
                              <div class="nav-notification__type nav-notification__type--primary">
                                <img class="svg" src="img/svg/inbox.svg" alt="inbox">
                              </div>
                              <div class="nav-notification__details">
                                <p>
                                Your Order  <span class="text-white bg-primary rounded px-1 py-1">' . $row['order_number'] . '</span> is ready.
                                </p>
                              </div>
                            </li> </a>
                            </form>';
                }
              } else {
                echo '<li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
              <div class="nav-notification__type nav-notification__type--primary">
                <img class="svg" src="img/svg/inbox.svg" alt="inbox">
              </div>
              <div class="nav-notification__details">
                <p>
                 Make an Order.
                </p>
              </div>
            </li>';
              }

              echo '</ul></div></div>';
              ?>
            </div>

          </li>

          <li class="nav-author">
            <div class="dropdown-custom">

              <a href="javascript:void(0);" class="nav-item-toggle"><img src="img/user.png" alt class="rounded-circle">
                <span class="nav-item__title text-white">
                  <?php echo $name; ?><i class="las la-angle-down nav-item__arrow"></i>
                </span>
              </a>
              <div class="dropdown-parent-wrapper">
                <div class="dropdown-wrapper">
                  <div class="nav-author__info">
                    <div class="author-img">
                      <img src="img/user.png" alt class="rounded-circle">
                    </div>
                    <div>
                      <h6>
                        <?php echo $name; ?>
                      </h6>
                      <span>Customer</span>
                    </div>
                  </div>
                  <div class="nav-author__options">
                    <ul>

                      <li>
                        <a href="profile">
                          <i class="uil uil-user"></i> Profile</a>
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
            <img src="img/svg/search.svg" alt="search" class="svg feather-search">
            <img src="img/svg/x.svg" alt="x" class="svg feather-x"></a> -->
          <a href="#" class="btn-author-action">
            <img class="svg" src="img/svg/more-vertical.svg" alt=""></a>
        </div>
      </div>
    </nav>
  </header>
  <main class="main-content">
    <div class="sidebar-wrapper">
      <div class="sidebar" id="sidebar" style="background:rgb(35, 35, 35)">
        <div class="sidebar__menu-group">
          <ul class="sidebar_nav">

            <li>
              <a href="index.php" class>
                <span class="nav-icon uil uil-create-dashboard " style="font-size:18px;"></span>
                <span class="menu-text text-white">Menu</span>

              </a>
            </li>


            <li>
              <a href="orderhistory.php" class>
                <span class="nav-icon uil uil-book " style="font-size:18px;"></span>
                <span class="menu-text text-white">Order History</span>

              </a>
            </li>
            <!-- <li>
              <a href="orders.php" class>
                <span class="nav-icon uil uil-book"></span>
                <span class="menu-text">My Orders</span>

              </a>
            </li> -->
            <!-- <li>
              <a href="#" class>
                <span class="nav-icon uil uil-comment"></span>
                <span class="menu-text">Info</span>

              </a>
            </li> -->
          </ul>
        </div>

      </div>
    </div>