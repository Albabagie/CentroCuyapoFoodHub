<?php
require_once 'connection.php';


function truncateWords($text, $limit)
{
  $words = explode(' ', $text);
  if (count($words) > $limit) {
    return implode(' ', array_slice($words, 0, $limit)) . '...';
  } else {
    return $text;
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="up/cuyapo/logo_cuyapo.jpg" type="">
  <title> Food Hub </title>

  <script src="sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="sweetalert2.min.css">
  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="up/css/bootstrap.css" />

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <!-- nice select  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" />
  <!-- font awesome style -->
  <link href="up/css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="up/css/sass/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="up/css/responsive.css" rel="stylesheet" />

</head>

<body>

  <div class="hero_area">
    <div class="bg-box">
      <img src="up/images/hero-bg.jpg" alt="">
    </div>
    <!-- header section strats -->
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container">
          <a class="navbar-brand" href="index.php">
            <span>
              Food Hub | Centro Cuyapo
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav  mx-auto ">
              <li class="nav-item active">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
              </li>
              <!-- <li class="nav-item">
                <a class="nav-link" href="menu.php">Menu</a>
              </li> -->
              <li class="nav-item">
                <a class="nav-link" href="about.php">About</a>
              </li>
              <!-- <li class="nav-item">
                <a class="nav-link" href="book.html">Book Table</a>
              </li> -->
            </ul>
            <div class="user_option">
              <a href="#" class="user_link index_button">
                <i class="fa fa-user" aria-hidden="true"></i>
              </a>
              <a href="#" class="cart_link">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                  <g>
                    <g>
                      <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248
                   c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                    </g>
                  </g>
                  <g>
                    <g>
                      <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48
                   C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064
                   c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4
                   C457.728,97.71,450.56,86.958,439.296,84.91z" />
                    </g>
                  </g>
                  <g>
                    <g>
                      <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                   c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                    </g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                </svg>
              </a>
              <form class="form-inline">
                <a href="#" class="btn  my-2 my-sm-0 nav_search-btn" type="submit">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </a>
              </form>
            </div>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section ">
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      Fast Food Restaurant
                    </h1>
                    <p>
                      Experience great food and friendly service. We use fresh ingredients to make every dish special. Visit us for a meal you’ll love.
                    </p>
                    <div class="btn-box">
                      <a href="#" class="btn1">
                        Order Now
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item ">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      Try our Meals
                    </h1>
                    <p>
                      Taste our best Foods, made with love and care. We serve fresh and tasty meals every day. Join us for a delicious dining experience.
                    </p>
                    <div class="btn-box">
                      <a href="#" class="btn1">
                        Order Now
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      Fast Food Restaurant
                    </h1>
                    <p>
                      Enjoy the best food, made fresh every day. Our chefs create delicious meals just for you. Come in and taste the difference.
                    </p>
                    <div class="btn-box">
                      <a href="#" type="button" class="btn1">
                        Order Now
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <ol class="carousel-indicators">
            <li data-target="#customCarousel1" data-slide-to="0" class="active"></li>
            <li data-target="#customCarousel1" data-slide-to="1"></li>
            <li data-target="#customCarousel1" data-slide-to="2"></li>
          </ol>
        </div>
      </div>

    </section>
    <!-- end slider section -->
  </div>

  <!-- offer section -->

  <section class="offer_section layout_padding-bottom">
    <div class="container text-center mt-3">
      <div class="ribbon-banner bg-warning" style="
      position: relative;
      background-color: #ffc107;
      color: white;
      padding: 15px 25px;
      display: inline-block;
      font-size: 1.5rem;
      font-weight: bold;
      text-transform: uppercase;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    ">
        <span style="
        position: absolute;
        top: 50%;
        left: -20px;
        width: 0;
        height: 0;
        border-top: 20px solid transparent;
        border-bottom: 20px solid transparent;
        border-right: 20px solid #ffc107;
        transform: translateY(-50%);
      "></span>

        Special Offer!

        <span style="
        position: absolute;
        top: 50%;
        right: -20px;
        width: 0;
        height: 0;
        border-top: 20px solid transparent;
        border-bottom: 20px solid transparent;
        border-left: 20px solid #ffc107;
        transform: translateY(-50%);
      "></span>
      </div>
    </div>
    <?php
    $discount_item = "SELECT * FROM promo WHERE promo_status = 'active'";
    $result_item = $conn->query($discount_item);

    if ($result_item->num_rows > 0):
    ?>

      <div class="offer_owl-carousel owl-carousel owl-theme">
        <?php while ($row_dis = $result_item->fetch_assoc()): ?>
          <!-- <div class="offer-carousel owl-carousel">    -->
          <div class="offer_container">
            <div class="container ">
              <div class="row">
                <div class="col-lg-12">
                  <div class="box px-5">
                    <div class="img-box mx-lg-5">
                      <?php $item_photo = "SELECT * FROM product_img WHERE product_id =" . $row_dis['item_id'];
                      $result_photo = $conn->query($item_photo);

                      if ($result_photo->num_rows > 0):
                        while ($row_photo = $result_photo->fetch_assoc()):
                      ?>
                          <img src="./uploads/<?php echo $row_photo['img_name'] ?>" alt=">>>">
                    </div>
                  <?php endwhile; ?>
                <?php endif; ?>
                <div class="detail-box">
                  <h5>
                    <?php echo $row_dis['promo_name'] ?>
                  </h5>
                  <h6>
                    <span><?php
                          $item_discount_ = (($row_dis['item_promo_price'] - $row_dis['item_current_price']) / $row_dis['item_promo_price']) * 100;
                          echo  number_format($item_discount_, 0);
                          ?>% </span>Off
                  </h6>
                  <a href="#">
                    Order Now <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                      <g>
                        <g>
                          <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248
                     c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                        </g>
                      </g>
                      <g>
                        <g>
                          <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48
                     C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064
                     c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4
                     C457.728,97.71,450.56,86.958,439.296,84.91z" />
                        </g>
                      </g>
                      <g>
                        <g>
                          <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                     c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                        </g>
                      </g>
                      <g>
                      </g>
                      <g>
                      </g>
                      <g>
                      </g>
                      <g>
                      </g>
                      <g>
                      </g>
                      <g>
                      </g>
                      <g>
                      </g>
                      <g>
                      </g>
                      <g>
                      </g>
                      <g>
                      </g>
                      <g>
                      </g>
                      <g>
                      </g>
                      <g>
                      </g>
                      <g>
                      </g>
                      <g>
                      </g>
                    </svg>
                  </a>
                </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else:  ?>
      <div></div>
    <?php endif; ?>
  </section>

  <!-- end offer section -->

  <!-- food section -->

  <section class="food_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Our Menu
        </h2>
      </div>
      <?php
      $categories = $conn->query("SELECT category as names FROM menu");

      if ($categories) {
        $res_categories = [];
        while ($row = $categories->fetch_assoc()) {
          $category = strtolower(str_replace(' ', '', $row['names']));
          if ($category == 'blackcrustpizza.co') {
            $category = 'blackcrustpizza';
          }

          $res_categories[] = $category;
        }
      } else {
        echo "Error: " . $conn->error;
      }


      ?>
      <ul class="filters_menu">
        <li class="active" data-filter="*">All</li>
        <?php
        foreach ($res_categories as $category) :
          $spaced_category = str_replace(
            ['blackcrustpizza', 'anothercategory', 'centrobarpulutan'],
            ['black crust pizza', 'another category', 'centro bar pulutan'],
            $category
          );
          $capitalized_category = ucwords($spaced_category);
          echo '<li data-filter=".' . $category . '">' . $capitalized_category . '</li>';
        endforeach;
        ?>
      </ul>

      <div class="filters-content">
        <div class="row grid">
          <?php
          $sql = "SELECT *
          FROM inventory i LEFT JOIN menu m ON m.category_id = i.product_category LEFT JOIN product_img pi ON pi.product_id = i.product_id WHERE state = 0";
          $result = mysqli_query($conn, $sql);
          while ($row = $result->fetch_assoc()) {

            $category = strtolower(str_replace(' ', '', $row['category']));
            if ($category == 'mamang\'s') {
              $category = 'mamangs';
            }
            if ($category == 'blackcrustpizza.co') {
              $category = 'blackcrustpizza';
            }
            // echo $category;
            echo '<div class="my-3 col-sm-6 col-lg-4 all ' . $category . '">
            <div class="box my-2">
              <div>
                <div class="img-box">
                  <img src="uploads/' . $row['img_name'] . '" alt="" class="rounded">
                </div>
                <div class="detail-box">
                  <p class="bg-light text-dark rounded text-center">
                    ' . $row['product_name'] . '
                  </p>
                  <p class="description-container">
                     <span class="limited-description">' . truncateWords($row['product_description'], 5) . '</span>
                      <span class="full-description" style="display: none;">' . $row['product_description'] . '</span>
                      
                  </p>
                  <div class="options">
                    <h6>
                      ' . $row['product_price'] . '
                    </h6>
                    <a href="#">
                      <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029"
                        style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                        <g>
                          <g>
                            <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248
                         c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                          </g>
                        </g>
                        <g>
                          <g>
                            <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48
                         C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064
                         c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4
                         C457.728,97.71,450.56,86.958,439.296,84.91z" />
                          </g>
                        </g>
                        <g>
                          <g>
                            <path
                              d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                         c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                          </g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>';
          }

          ?>



        </div>
        <div class="btn-box">
          <a href="#">
            View More
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- end food section -->

  <!-- about section -->

  <section class="about_section layout_padding">
    <div class="container  ">

      <div class="row">
        <div class="col-md-6 ">
          <div class="img-box">
            <img src="up/cuyapo/logo_cuyapo.jpg" href="" alt="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                We Are Food Hub | Centro Cuyapo
              </h2>
            </div>
            <p>
              Centro Cuyapo Food Hub is a casual restaurant located in Cuyapo, Nueva Ecija, near landmarks like the Town Hall, public plaza, and market. it features 6 food hubs offering a variety of affordable meals, including seafood boodles, sizzling dishes, chicken, fish, and classic cuisine. We also serve unlimited BBQ, pizzas, burgers, snacks, and beverages. The restaurant operates daily from 10 AM to 10 PM.
            </p>
            <a href="#">
              Read More
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end about section -->

  <!-- book section -->
  <!-- <section class="book_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>
          Book A Table
        </h2>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form_container">
            <form action="">
              <div>
                <input type="text" class="form-control" placeholder="Your Name" />
              </div>
              <div>
                <input type="text" class="form-control" placeholder="Phone Number" />
              </div>
              <div>
                <input type="email" class="form-control" placeholder="Your Email" />
              </div>
              <div>
                <select class="form-control nice-select wide">
                  <option value="" disabled selected>
                    How many persons?
                  </option>
                  <option value="">
                    2
                  </option>
                  <option value="">
                    3
                  </option>
                  <option value="">
                    4
                  </option>
                  <option value="">
                    5
                  </option>
                </select>
              </div>
              <div>
                <input type="date" class="form-control">
              </div>
              <div class="btn_box">
                <button>
                  Book Now
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="col-md-6">
          <div class="map_container ">
            <div id="googleMap"></div>
          </div>
        </div>
      </div>
    </div>
  </section> -->
  <!-- end book section -->

  <!-- client section -->

  <section class="client_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center psudo_white_primary mb_45">
        <h2>
          What our Customers say
        </h2>
      </div>
      <div class="carousel-wrap row ">
        <div class="owl-carousel client_owl-carousel">
          <div class="item">
            <div class="box">
              <div class="detail-box">
                <p>
                  I highly recommend Centro Cuyapo Food Hub. Masarap at maganda ang place, nakaka relax tahimik lang at presko tamang tama sa mag couple nagusto lang mag date

                </p>
                <h6>
                  Moana Michell
                </h6>
                <p>
                  magna aliqua
                </p>
              </div>
              <div class="img-box">
                <img src="up/images/client1.jpg" alt="" class="box-img">
              </div>
            </div>
          </div>
          <div class="item">
            <div class="box">
              <div class="detail-box">
                <p>
                  I highly recommend Centro Cuyapo Food Hub. Sa dami nang kanilang stall talagang mabubusog ka sa murang halaga, not once but twice na kaming naka visit dito at hinde parin nagbabago yung kanilang service.

                </p>
                <h6>
                  Mike Hamell
                </h6>
                <p>
                  magna aliqua
                </p>
              </div>
              <div class="img-box">
                <img src="up/images/client2.jpg" alt="" class="box-img">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end client section -->

  <!-- footer section -->
  <footer class="footer_section">
    <div class="container">
      <div class="row">
        <div class="col-md-4 footer-col">
          <div class="footer_contact">
            <h4>
              Contact Us
            </h4>
            <div class="contact_link_box">
              <a href="#">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>
                  Address: District VII Cuyapo, Nueva Ecija (Behind New Century, near Producers Bank and besides Pangalilinan's Gas Station)
                </span>
              </a>
              <a href="#">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>
                  Phone Number: +639454917094
                </span>
              </a>
              <a href="#">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>
                  Email:Centrocuyapofoodhuba@gmail.com
                </span>
              </a>
              <a href="#">
                <i class="fa fa-facebook" aria-hidden="true"></i>
                <span>
                  Facebook page: <a href="https://www.facebook.com/profile.php?id=100076167973746">Click here</a>
                </span>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <a href="index.php" class="footer-logo">
            Food Hub | Centro Cuyapo
          </a>
          <p>
            Start your day on the right note! Because all it takes is one special meal to brighten up your mood.
          </p>
          <div class="footer_social">
            <a href="#">
              <i class="fa fa-facebook" aria-hidden="true"></i>
            </a>
            <a href="#">
              <i class="fa fa-twitter" aria-hidden="true"></i>
            </a>
            <a href="#">
              <i class="fa fa-linkedin" aria-hidden="true"></i>
            </a>
            <a href="#">
              <i class="fa fa-instagram" aria-hidden="true"></i>
            </a>
            <a href="#">
              <i class="fa fa-pinterest" aria-hidden="true"></i>
            </a>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <h4>
            Opening Hours
          </h4>
          <p>
            Everyday
          </p>
          <p>
            10 AM to 10 PM
          </p>
        </div>
      </div>
      <!-- <div class="col-md-4 footer-col">
        <h4>
          Opening Hours
        </h4>
        <p>
          Everyday
        </p>
        <p>
          10 AM to 10 PM
        </p>
      </div> -->
    </div>
    <div class="footer-info">
      <p>
        &copy; <span id="displayYear"></span> All Rights Reserved By
        <a href="index.php">Food Hub | Centro Cuyapo</a><br><br>
        <!-- https://html.design/ -->
      </p>
    </div>
    </div>
  </footer>
  <!-- footer section -->

  <script src="sweetalert2.all.min.js"></script>
  <!-- jQery -->
  <script src="up/js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js -->
  <script src="up/js/bootstrap.js"></script>
  <!-- owl slider -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- isotope js -->
  <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
  <!-- nice select -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
  <!-- custom js -->
  <script src="up/js/custom.js"></script>


  <script src="js/loggedIn.js"></script>
  <!-- Include this JavaScript in your HTML -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Select all description containers
      const descriptionContainers = document.querySelectorAll('.description-container');

      descriptionContainers.forEach(container => {
        const limitedDescription = container.querySelector('.limited-description');
        const fullDescription = container.querySelector('.full-description');

        // Count the words in the full description
        const wordCount = fullDescription.textContent.split(' ').length;

        // Add the "View More" button if word count exceeds 5
        if (wordCount > 5) {
          // Create and insert the button
          const button = document.createElement('button');
          button.className = 'view-more-btn nav-link nav-light px-1 py-0 rounded';
          button.textContent = 'View More';
          container.appendChild(button);

          // Add event listener to the button
          button.addEventListener('click', function() {
            if (fullDescription.style.display === 'none' || fullDescription.style.display === '') {
              limitedDescription.style.display = 'none';
              fullDescription.style.display = 'block'; // Change to 'block' if needed
              this.textContent = 'View Less';
            } else {
              limitedDescription.style.display = 'block';
              fullDescription.style.display = 'none';
              this.textContent = 'View More';
            }
          });
        }
      });
    });
  </script>
  <script>
    $(".offer_owl-carousel").owlCarousel({
      loop: true,
      // margin: 0,
      dots: false,
      nav: false,
      navText: [],
      autoplay: true,
      autoplayHoverPause: true,
      // navText: [
      //     '<i class="fa fa-angle-left" aria-hidden="true"></i>',
      //     '<i class="fa fa-angle-right" aria-hidden="true"></i>'
      // ],
      responsive: {
        0: {
          items: 1
        },
        400: {
          items: 2
        },
        1000: {
          items: 2
        }
      }
    });
  </script>
</body>

</html>