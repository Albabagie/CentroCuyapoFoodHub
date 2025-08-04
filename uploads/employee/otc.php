<?php
include ('sidebar.php');
?>

<div class="contents">
    <div class="crm demo6 mb-25">
        <div class="container-fluid mx-lg-4">
            <div class="row ">
                <div class="col-lg-12 px-md-4">
                    <div class="breadcrumb-main">
                        <b class="text-capitalize breadcrumb-title">Stall</b>
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Stalls</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb-main user-member justify-content-sm-between ">
                            <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
                                <div
                                    class="d-flex align-items-center user-member__title justify-content-center me-sm-25">

                                </div>


                            </div>


                        </div>
                    </div>
                </div>



                <div class="row col-lg-12">
                    <?php
                    $sqls = "SELECT * FROM menu";
                    $result = $conn->query($sqls);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="row col-sm-4 my-sm-2 me-sm-1">
                <div class="card position-relative border-1 border-lighten">
     
          <img src="../uploads/' . $row["image_stall"] . '" class="card-img-top p-4" alt="...">';
                            if ($row['status'] == 'available') {
                                echo '<h5 class="text-center my-sm-2 rounded-pill bg-success text-capitalize text-white">' . $row["status"] . '</h5>';
                            } else {
                                echo '<h5 class="text-center my-sm-2 rounded-pill bg-danger text-capitalize">' . $row["status"] . '</h5>';
                            }

                            echo '<div class="card-body">
                    <h5 class="card-title">' . $row["category"] . '</h5>
                    <p class="card-text">Some content</p>
                </div>
                <div class="card-footer row justify-content-around">
                  <a  href="viewmenu.php?category_id=' . $row["category_id"] . '"  class="btn btn-secondary  me-sm-1 w-50" type="button">View Menu</a>

                </div>
            </div>
        </div>';
                        }
                    } else {
                        echo '0';
                    }

                    ?>
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgYKHZB_QKKLWfIRaYPCadza3nhTAbv7c"></script>
<script src="js/plugins.min.js"></script>
<script src="js/script.min.js"></script>
</body>


</html>