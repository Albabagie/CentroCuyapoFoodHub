<?php include ('session.php') ?>
<!doctype html>
<html lang="en" dir="ltr">

<!-- Mirrored from demo.dashboardmarket.com/hexadash-html/ltr/demo6.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 07 Oct 2023 01:02:49 GMT -->

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>FoodHub | Employee</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/plugin.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="./css/customize.css">
  <!-- <link rel="stylesheet" href="styles.css"> -->
  <!-- <link rel="icon" type="image/png" sizes="16x16" href="img/favicon.png"> -->
  <link rel="stylesheet" href="unicons.iconscout.com/release/v4.0.0/css/line.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">


  <style>
    .image-hover-effect {
      transition: transform 0.3s;
      transform-origin: center;
    }

    .image-hover-effect:hover {
      transform: scale(1.03);
    }

    .checkbox-list__single {
      margin-right: 15px;
      /* Adjust the margin as needed */
    }
  </style>

</head>

<body class="layout-light side-menu">
  <div class="mobile-search">
    <form action="http://demo.dashboardmarket.com/" class="search-form">
      <img src="img/svg/search.svg" alt="search" class="svg">
      <input class="form-control me-sm-2 box-shadow-none" type="search" placeholder="Search..." aria-label="Search">
    </form>
  </div>
  <div class="mobile-author-actions "></div>
  <header class="header-top">
    <nav class="navbar navbar-light ">
      <div class="navbar-left ">
        <div class="logo-area ">
          <!-- <a class="navbar-brand" href="index-2.html"> -->
          <!-- <img class="dark" src="img/logo-dark.png" alt="logo">
              <img class="light" src="img/logo-white.png" alt="logo"> -->
          <!-- </a> -->
          <a href="#" class="sidebar-toggle">
            <img class="svg" src="img/svg/align-center-alt.svg" alt="img"></a>
        </div>
      </div>
      <div class="navbar-right">
        <ul class="navbar-right__menu">
          <?php
          $sql = "SELECT * FROM orders WHERE status = 'pending'";
          $result = $conn->query($sql);

          $notificationCount = $result->num_rows;
          ?>

          <li class="nav-notification p-0">
            <div class="dropdown-custom">
              <a href="javascript:;" class="nav-item-toggle position-relative py-1">

                <img class="w-100 mr-md-4 h-100" src="img/svg/alarm.svg" alt="img">
                <span class="position-absolute top-0 start-0">
                  <?php

                  if ($notificationCount > 0) {
                    echo '<span class=" w-75 h-75 badge-circle badge-warning ms-1">' . $notificationCount . '</span>';
                  } ?>
                </span>
              </a>
              <?php
              $sql = "SELECT * FROM orders WHERE status = 'pending'";
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
            <a href="listorders.php?order_id=' . $row['order_id'] . '">
            <li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
                  <div class="nav-notification__type nav-notification__type--primary">
                    <img class="svg" src="img/svg/inbox.svg" alt="inbox">
                  </div>
                  <div class="nav-notification__details">
                    <p>
                      Customer has New Order.
                    </p>
                  </div>
                </li> </a>';

                }
              } else {
                echo '<li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
              <div class="nav-notification__type nav-notification__type--primary">
                <img class="svg" src="img/svg/inbox.svg" alt="inbox">
              </div>
              <div class="nav-notification__details">
                <p>
                 No pending Order Good Job!.
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
              <a href="javascript:;" class="nav-item-toggle"> <img src="img/user.png" alt class="rounded-circle">
                <span class="nav-item__title">Employee<i class="las la-angle-down nav-item__arrow"></i></span>
              </a>
              <div class="dropdown-parent-wrapper">
                <div class="dropdown-wrapper">
                  <div class="nav-author__info">
                    <div class="author-img">
                      <img src="img/user.png" alt class="rounded-circle">
                    </div>
                    <div>
                      <h6>
                        <?php #echo $name;                                                                                                                             ?>
                      </h6>
                      <span>Employee</span>
                    </div>
                  </div>
                  <div class="nav-author__options">
                    <ul>

                      <li>
                        <a href="profile.php">
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
          <a href="#" class="btn-search">
            <img src="img/svg/search.svg" alt="search" class="svg feather-search">
            <img src="img/svg/x.svg" alt="x" class="svg feather-x"></a>
          <a href="#" class="btn-author-action">
            <img class="svg" src="img/svg/more-vertical.svg" alt=""></a>
        </div>
      </div>
    </nav>
  </header>
  <main class="main-content">
    <div class="sidebar-wrapper">
      <div class="sidebar sidebar-collapse" id="sidebar">
        <div class="sidebar__menu-group">
          <ul class="sidebar_nav ">


            <li>
              <a href="index.php" class>
                <span class="nav-icon uil uil-box "></span>
                <span class="menu-text">Dashboard</span>

              </a>
            </li>
            <li>
              <a href="listorders.php" class>
                <span class="nav-icon uil uil-list-ul "></span>
                <span class="menu-text ">Order Records</span>

              </a>
            </li>
            <li>
              <a href="otc.php" class>
                <span class="nav-icon uil uil-users-alt"></span>
                <span class="menu-text">OTC Order</span>

              </a>
            </li>
            <li>
              <a href="listotc.php" class>
                <span class="nav-icon uil uil-users-alt"></span>
                <span class="menu-text">OTC list</span>

              </a>
            </li>
            <li>
              <a href="#" class>
                <span class="nav-icon uil uil-user"></span>
                <span class="menu-text ">Info</span>

              </a>
            </li>



          </ul>
          <!-- <div class="bottom-0 my-md-2 mx-md-2 p-2 position-absolute">
            <img src="img/about-img.png" alt="about" width="200" height="200" class="position-relative">
          </div> -->
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.all.min.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgYKHZB_QKKLWfIRaYPCadza3nhTAbv7c"></script>