<?php
include ('sidebar.php');
?>

<div class="contents" style="background:rgb(252,250,241)">
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




                <div class="row col-lg-12">
                    <?php
                    $sqls = "SELECT * FROM menu WHERE status = 'available'";
                    $result = $conn->query($sqls);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="row col-sm-4 my-sm-2 me-sm-1">
                <div class="card position-relative border-1 border-lighten" style="background:rgb(248,239,212)">
     
          <img src="../uploads/' . $row["image_stall"] . '" class="card-img-top p-4" alt="...">';
                            if ($row['status'] == 'available') {
                                echo '<h5 class="text-center my-sm-2 rounded-pill bg-success text-capitalize text-white">' . $row["status"] . '</h5>';
                            } else {
                                echo '<h5 class="text-center my-sm-2 rounded-pill bg-danger text-capitalize">' . $row["status"] . '</h5>';
                            }

                            echo '<div class="card-body">
                    <h5 class="card-title">' . $row["category"] . '</h5>
                    <p class="card-text">'. $row["stall_description"].'</p>
                </div>
                <div class="card-footer row justify-content-around" style="background:rgb(229, 153, 52)">
                  <a  href="viewmenu.php?category_id=' . $row["category_id"] . '"  class="btn btn-md me-sm-1 text-white" type="button" style="background:rgb(19, 39, 67)">View Menu</a>

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