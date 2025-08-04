<?php include ('session.php'); ?>
<!doctype html>
<html lang="en" dir="ltr">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Food Hub | Customer</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&amp;display=swap" rel="stylesheet">

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
</style>

<body class="layout-light side-menu">
  <div class="mobile-search">
    <form action="http://demo.dashboardmarket.com/" class="search-form">
      <img src="img/svg/search.svg" alt="search" class="svg">
      <input class="form-control me-sm-2 box-shadow-none" type="search" placeholder="Search..." aria-label="Search">
    </form>
  </div>
  <div class="mobile-author-actions"></div>
  <header class="header-top">
    <nav class="navbar navbar-light">
      <div class="navbar-left">
        <div class="logo-area ">

          <a href="#" class="sidebar-toggle">
            <img class="svg" src="img/svg/align-center-alt.svg" alt="img"></a>
        </div>

      </div>
      <div class="navbar-right">
        <ul class="navbar-right__menu">


          <?php

          ?>

          <li class="nav-notification">
            <div class="dropdown-custom">
              <a href="javascript:;" class="nav-item-toggle <?php ?>">
                <img class="svg" src="img/svg/alarm.svg" alt="img">
              </a>
              <?php

              ?>
            </div>

          </li>

          <li class="nav-author">
            <div class="dropdown-custom">

              <a href="javascript:;" class="nav-item-toggle"><img src="img/user.png" alt class="rounded-circle">
                <span class="nav-item__title">
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
      <div class="sidebar" id="sidebar">
        <div class="sidebar__menu-group">
          <ul class="sidebar_nav">

            <li>
              <a href="index.php" class>
                <span class="nav-icon uil uil-create-dashboard"></span>
                <span class="menu-text">Menu</span>

              </a>
            </li>


            <li>
              <a href="orderhistory.php" class>
                <span class="nav-icon uil uil-shopping-basket"></span>
                <span class="menu-text">Order History</span>

              </a>
            </li>
            <li>
              <a href="orders.php" class>
                <span class="nav-icon uil uil-book"></span>
                <span class="menu-text">My Orders</span>

              </a>
            </li>
            <li>
              <a href="info.php" class>
                <span class="nav-icon uil uil-comment"></span>
                <span class="menu-text">Info</span>

              </a>
            </li>
          </ul>
        </div>

      </div>
    </div>