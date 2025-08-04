<?php include('session.php') ?>
<!doctype html>
<html lang="en" dir="ltr">

<!-- Mirrored from demo.dashboardmarket.com/hexadash-html/ltr/demo6.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 07 Oct 2023 01:02:49 GMT -->

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>FoodHub | Employee</title>
  <link rel="shortcut icon" href="../up/cuyapo/logo_cuyapo.jpg" type="">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/plugin.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="./css/customize.css">

  <link rel="stylesheet" href="unicons.iconscout.com/release/v4.0.0/css/line.css">



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
    /* Initial state of the modal before it appears */
#prepare_order, #paid_order {
    display: none;
    opacity: 0;
    transform: translateY(-30px); /* Move it slightly up */
    transition: all 0.3s ease-in;
}

/* State of the modal when it's shown */
#prepare_order.show, #paid_order.show {
    display: block;
    opacity: 1;
    transform: translateY(0); /* Slide it down to the original position */
}
.body_upload {
    background-color: transparent;
}

.content-flu {
    position: relative;
    width: 100%; 
   
}

.id-image {
    width: 100%; /* Image will take the full width of the container */
    height: auto;
    display: block;
}

.upload-btn {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 10;
}
.upload-label {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: transparent;
    color: black;
    font-size: 16px;
    cursor: pointer;
    z-index: 5;
    display: none; /* Hidden by default */
}

.upload-label {
            display: none; /* Initially hide the button */
            background: rgba(0,0,0,0.5);
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 4px;
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            cursor: pointer;
        }
        .upload-btn {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .update-btn {
            display: none; /* Initially hide the button */
        }
        .wooden-table {
    width: 100%;
    height: 400px; /* Adjust the height as needed */
    background-image: url('./img/wooden-table.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 20px; /* Adjust padding as needed */
    display: flex;
    align-items: center;
    justify-content: center;
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
  <div class="mobile-author-actions">
  </div>
  <header class="header-top">
    <nav class="navbar" style="background:rgb(35,35,35)">
      <div class="navbar-left px-4">
          <a href="#" class="sidebar-toggle">
            <i class="uil uil-bars text-light"></i>
          </a>
     
      </div>
      <div class="navbar-right">
        <ul class="navbar-right__menu">
          <?php
                      $sql = "SELECT * FROM orders  WHERE  status = 0 AND DATE(order_date) like  '%$current_date%'";

          $result = $conn->query($sql);

          $notificationCount = $result->num_rows;
          ?>

          <li class="nav-notification p-0">
            <div class="dropdown-custom">
              <a href="javascript:;" class="nav-item-toggle position-relative py-1">

               <i class="uil uil-bell fs-5 text-light"></i>
                <span class="position-absolute top-0 start-0">
                  <?php

                  if ($notificationCount > 0) {
                    echo '<span class=" w-75 h-75 badge-circle badge-warning ms-1">' . $notificationCount . '</span>';
                  } ?>
                </span>
              </a>
              <?php
              $sql = "SELECT * FROM orders  WHERE  status = 0 AND DATE(order_date) like  '%$current_date%'";
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
            <a href="index.php">
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
              <a href="javascript:;" class="nav-item-toggle"> <img src="<?php echo $image_path ?>" alt class="rounded-circle">
                <span class="nav-item__title text-white"><?php echo $name ?><i class="las la-angle-down nav-item__arrow"></i></span>
              </a>
              <div class="dropdown-parent-wrapper">
                <div class="dropdown-wrapper">
                  <div class="nav-author__info">
                    <div class="author-img">
                      <img src="<?php echo $image_path ?>" alt class="rounded-circle">
                    </div>
                    <div>
                      <h6>
                        <?php #echo $name;                                                                                                                             
                        ?>
                      </h6>
                      <span><?php echo $name ?></span>
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
      
          <a href="#" class="btn-author-action">
            <img class="svg" src="img/svg/more-vertical.svg" alt=""></a>
        </div>
      </div>
    </nav>
  </header>
  <main class="main-content">
    <div class="sidebar-wrapper">
      <div class="sidebar sidebar-collapse" id="sidebar" style="background:rgb(35,35,35)">
        <div class="sidebar__menu-group">
          <ul class="sidebar_nav ">


            <li>
              <a href="index.php" class>
                <span class="nav-icon uil uil-box "></span>
                <span class="menu-text text-white">Dashboard</span>

              </a>
            </li>
            <li>
              <a href="listorders.php" class>
                <span class="nav-icon uil uil-list-ul "></span>
                <span class="menu-text text-white">Order Records</span>

              </a>
            </li>
            <li>
              <a href="otc.php" class>
                <span class="nav-icon uil uil-users-alt"></span>
                <span class="menu-text text-white">OTC Menu</span>

              </a>
            </li>
            <li>
              <a href="listotc.php" class>
                <span class="nav-icon uil uil-users-alt"></span>
                <span class="menu-text text-white">OTC list</span>

              </a>
            </li>
          


          </ul>
        
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.all.min.js"></script>