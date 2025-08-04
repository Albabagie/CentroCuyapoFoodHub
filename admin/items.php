<?php
include('sidebar.php');


?>

      <div class="contents">
        <div class="crm demo6 mb-25">
          <div class="container-fluid mx-lg-4">
            <div class="row ">
              <div class="col-lg-12">
                <div class="breadcrumb-main">
                  <b class="text-capitalize breadcrumb-title">Item</b>
                  <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Items</li>
                      </ol>
                    </nav>
                  </div>
                </div>
              </div>
           
     
     
              
           <div class="row">
            <div class="col-lg-12">
              <div class="breadcrumb-main user-member justify-content-sm-between ">
                <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
                  <div class="d-flex align-items-center user-member__title justify-content-center me-sm-25">
                    <h4 class="text-capitalize fw-500 breadcrumb-title">Items</h4>
                    <span class="sub-title ms-sm-25 ps-sm-25">Home</span>
                  </div>
                  
              
                </div>
                
                <div class="action-btn">
                  <a href="additem.php" class="btn px-15 btn-secondary">
                    <i class="las la-plus fs-16"></i>Add Item</a>
                  <div class="modal fade new-member " id="new-member" role="dialog" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content  radius-xl">
                        <div class="modal-header">
                          <h6 class="modal-title fw-500" id="staticBackdropLabel"></h6>
                          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <img src="img/svg/x.svg" alt="x" class="svg">
                          </button>
                        </div>

    

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        


               <div class="row">
         <?php
$sql = "SELECT * FROM item ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

    echo '
        
    <div  class="col-md-6 col-sm-12 mb-20 image-hover-effect position-relative">';
      if($row['item_status'] == 'available'){
          echo '<div class="px-sm-4 py-sm-4 position-absulote top-0 end-0 w-50">
          <span class="badge-card-success d-flex justify-content-center font-size-sm w-100">'.$row['item_status'].'</span>
          </div>';
      }else{
        echo '<div class="px-sm-4 py-sm-4 position-absulote top-0 end-0 w-50">
        <span class="badge-card-danger d-flex justify-content-center font-size-sm w-100">'.$row['item_status'].'</span>
        </div>';
      }
     echo '
        <div class="media py-30 ps-30 pe-20 bg-white radius-xl users-list">';
      
        echo '<img class="me-20 rounded-circle wh-80 bg-opacity-primary " src="../uploads/'.$row["item_img"].'" alt="Generic placeholder image">
   
            <div class="media-body d-xl-flex users-list-body">
                <div class="flex-1 pe-xl-30 users-list-body__title">
                    <h6 class="mt-0 fw-500">'.$row["item_name"].'</h6>
                    
                    <p class="mb-0">'.$row["item_description"].'</p>
                    <div class="users-list-body__bottom">
                        <span><span class="fw-600">Price:</span>
                        <span><span class="fw-600">₱'.number_format($row["item_price"]).'</span></span>
                        <br>
               
                    </div>
                    
               </div>';
              
               echo '<div class="users-list__button mt-xl-0 mt-15">
                    <a  href="viewitem.php?item_id='.$row["item_id"].'"class="btn btn-secondary btn-default btn-squared text-capitalize px-20 mt-5 global-shadow">View item</a>
                  <!---added po delete button--->
                    <form action="" method="post">
                        <input type="hidden" name="item_id" value="'.$row["item_id"].'">
                        
                    </form>
                </div>
            </div>
        </div>';
   
    
echo ' </div>  ';




    }
} else {
    echo "0 results";
}
$conn->close();
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