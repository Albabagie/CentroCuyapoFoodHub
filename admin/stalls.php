<?php
include('sidebar.php');

if(isset($_POST['delete_stall'])){
  $stal_id = $_POST['id_stall'];

  $stalls = "SELECT * FROM menu WHERE category_id = ? AND status = 'available'";
  $result_stall = $conn->prepare($stalls);
  $result_stall->bind_param('i', $stal_id);
  $result_stall->execute();
  $query_result = $result_stall->get_result();

  if($query_result->num_rows > 0){
      $update_stall = "UPDATE menu SET status = 'not' WHERE category_id = ?";
      $result_update  = $conn->prepare($update_stall);
      $result_update->bind_param("i", $stal_id);
      
      if($result_update->execute()){
        echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire({
                      title: "Stall has been removed.",
                      text: "Nice!",
                      icon: "info"
                  });
              });
              </script>';
      }
  }
}

if(isset($_POST['active_stall'])){
  $stal_id = $_POST['id_stall'];

  $stalls = "SELECT * FROM menu WHERE category_id = ? AND status = 'not'";
  $result_stall = $conn->prepare($stalls);
  $result_stall->bind_param('i', $stal_id);
  $result_stall->execute();
  $query_result = $result_stall->get_result();

  if($query_result->num_rows > 0){
      $update_stall = "UPDATE menu SET status = 'available' WHERE category_id = ?";
      $result_update  = $conn->prepare($update_stall);
      $result_update->bind_param("i", $stal_id);
      
      if($result_update->execute()){
        echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire({
                      title: "Stall Activated Successfully.",
                      text: "Nice!",
                      icon: "success"
                  });
              });
              </script>';
      }
  }
}

?>

<div class="contents" style="background:rgb(252,250,241)">
  <div class="crm demo6 mb-25">
    <div class="container-fluid mx-lg-4">
      <div class="row px-3">
        <div class="col-lg-12 px-md-4">
          <div class="breadcrumb-main m-2">
            <b class="text-capitalize breadcrumb-title">Stall</b>
            <div class="breadcrumb-action justify-content-center flex-wrap">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Dashboard
                    </a></li>
                  <li class="breadcrumb-item active" aria-current="page">Stalls</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>



        <div class="row">
          <div class="col-lg-12">
            <div class="breadcrumb-main user-member justify-content-sm-between m-2">
              <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
                <div class="d-flex align-items-center user-member__title justify-content-center me-sm-25">
                <div class="row">
                  <button data-toggle="modal" data-target="#listed_stalls" type="button" class="btn btn-gray-hover"><i class="uil uil-list-ol mx-2"><span class="text-white"></span></i>Recent stall</button>
                </div>
                </div>
                <!-- -->
                <div class="modal fade " id="listed_stalls" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="listed_stallsLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="listed_stallsLabel">Stall | Cuyapo Food Hub</h5>
                      <button type="button" id="close_promo" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                        <!--  -->
                    <div class="modal-body gap-2">
                        <?php 
                          $stall_inactive = $conn->query("SELECT * FROM menu WHERE status = 'not'");
                          if($stall_inactive->num_rows > 0):
                            while($row = $stall_inactive->fetch_assoc()):?>
                              <div class="card-body border border-extra-light rounded my-2">
                                  <div class="d-flex justify-content-between ">
                                    <div class="position-relative  w-50">
                                      <?php echo $row['category'] ?> 
                                  </div>
                                 
                                    <div>
                                    <span>
                                      <form method="POST" id="activeForm">
                                        <input type="hidden" name="id_stall" value="<?php echo $row['category_id'] ?>">
                                        <input type="hidden" name="active_stall">
                                        <button type="button" class="btn btn-success-hover" onclick="activeStall()">Open Stall</button>
                                      </form>
                                    </span>
                                  </div>
                                  </div>
                              </div>
                            <?php endwhile;?>
                           <?php else: ?> 
                            <div>
                              <span>No inactive stalls found.</span>
                            </div>
                          <?php 
                          endif;
                        ?>
                    </div>
              </div>
          </div>
                </div>
              </div>
             
              <div class="row action-btn">
                <a href="addstall.php" class="btn text-white px-15 mx-sm-2" style="background:rgb(19, 39, 67)">
                  <i class="las la-plus fs-16"></i>Add Stall</a>
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



        <div class="d-flex flex-wrap justify-content-sm-between px-2 gap-2">
          <?php
          $sqls = "SELECT * FROM menu WHERE status = 'available'";
          $result = $conn->query($sqls);

          if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
              echo '<div class="row col-sm-4 my-sm-2 me-sm-1">
                <div class="card position-relative border-1 border-lighten mx-1" style="background:rgb(248,239,212)">
     
          <img src="../uploads/' . $row["image_stall"] . '" class="card-img-top p-4" alt="...">';
              if ($row['status'] == 'available') {
                echo '<h5 class="text-center my-sm-2 rounded-pill bg-success text-capitalize">' . $row["status"] . '</h5>';
              } else {
                echo '<h5 class="text-center my-sm-2 rounded-pill bg-danger text-capitalize">' . $row["status"] . '</h5>';
              }

              echo '<div class="card-body">
                    <h5 class="card-title">' . $row["category"] . '</h5>
                    <p class="card-text">';
                    if($row['stall_description'] != NULL || !empty( $row['stall_description'])) {
                      echo $row['stall_description'];
                    } else{
                      echo 'No Description Set';
                    }
                    echo'</p>
                </div>
                <div class="card-footer row justify-content-around gap-2" style="background:rgb(229, 153, 52)">
                  <a  href="viewmenu.php?category_id=' . $row["category_id"] . '"  class="btn text-white me-sm-1" type="button" style="background:rgb(19, 39, 67)">View Menu</a>
                  <a  href="editstore.php?category_id=' . $row["category_id"] . '"  class=" btn text-white me-sm-1" type="button" style="background:rgb(19, 39, 67)">Edit</a>';
                  ?>
                  <div class="w-100">
                <form id="deleteForm" method="POST" class="text-center px-4">
                    <input type="hidden" name="id_stall" value="<?php echo $row['category_id']; ?>">
                    <input type="hidden" name="delete_stall" value="true"> <!-- Hidden input to trigger PHP check -->
                    <button type="button" class="btn btn-danger rounded w-100" onclick="confirmDelete()">Delete Stall</button>
                </form>
            </div>

                 <?php
                echo '</div>
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

<script src="js/plugins.min.js"></script>
<script src="js/script.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete() {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete this stall?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        popup: 'swal2-hide'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm').submit();
        }
    });
}
</script>

<script>
function activeStall() {
      Swal.fire({
        target: document.getElementById('listed_stalls'),
        title: 'Are you sure?',
        text: "Do you want to activate this stall?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, activate it!',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('activeForm').submit();
        } 
    });
  
      
}
</script>
</body>

</html>