<?php
include('sidebar.php');
$category_id = $_GET['category_id'];
?>


<div class="contents" style="background: rgb(252,250,241)">
  <div class="container-fluid ">
    <div class="row">
      <div class="col-lg-12">
        <div class="shop-breadcrumb">
          <div class="breadcrumb-main">
            <h4 class="text-capitalize breadcrumb-title px-2">item List</h4>
            <div class="breadcrumb-action justify-content-center flex-wrap">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Menu</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="products mb-3 px-2">
    <div class="container-fluid">
      <div class="card product-details border-0" style="background:rgb(248,239,212);">
        <div class="card-header w-100 d-flex justify-content-center " style="background:rgb(250,139,12)">
          <h1 class="text-white fw-bold">
<?php $category_name = $conn->query("SELECT * FROM menu WHERE category_id = '$category_id'");
$result_cat = $category_name->fetch_assoc()['category'];
  echo $result_cat;
?>
</h1>
        </div>
        <div class="product-item p-sm-30 p-15 ">
          <div class="row justify-content-center gap-2">

            <!-- container -->
            <?php
            $sql = "SELECT * FROM inventory i LEFT JOIN product_img p ON p.product_id = i.product_id WHERE i.state != 1 AND i.product_category = '$category_id'";
            $res = $conn->query($sql);

            if ($res->num_rows > 0) {
              while ($row = $res->fetch_assoc()) {
                $item_id = $row['product_id'];
                $state = $row['state'];
                if ($row['availability'] == 'available') {
                  echo '
                      <div class="card col-lg-3 col-md-5 mx-lg-4 my-lg-4 my-md-2">';
                  
                  echo '<div class="card-header position-relative p-1">
                              <h2 class="position-absolute">
                              <span class="text-dark" style="font-size:16px">' . $row['product_name'] . '</span>';
                               $sql_price_promo = "SELECT * FROM promo WHERE item_id = '$item_id' AND promo_status = 'active'";
                              $result_price = $conn->query($sql_price_promo);
                              if($result_price->num_rows > 0){
                                while ($row_price = $result_price->fetch_assoc()){
                                  echo '<span class="position-relative text-white px-1 py-1" style="font-size:16px;background:rgb(19, 39, 67);border-radius:10px;">₱ '.number_format($row_price['item_current_price'],2).'</span>';
                                }
                              } else{
                                  echo '<span class="position-relative text-white px-1 py-1" style="font-size:16px;background:rgb(19, 39, 67);border-radius:10px;">₱ '.number_format($row['product_price'],2).'</span>';
                              }
                               echo '</h2>
                            
                       </div>';
                       echo '<div class="ratio ratio-4x3">';
                       echo '<img src="../uploads/' . $row['img_name'] . '" class="card-img-top img-fluid">';
                       echo '</div>';
                       
                  if ($row['availability'] == 'available') {
                    echo "<span class='rounded-pill bg-success text-center text-capitalize my-2'>" . $row['availability'] . "</span>";
                  } else {
                    echo "<span class='rounded-pill bg-danger text-center'>" . $row['availability'] . "</span>";
                  }
                  echo '<div class="card-body rounded">
                             <p>' . $row['product_description'] . '</p>
                       </div>
                       <div class="card-footer">
                           <a href="viewitem.php?product_id=' . $row['product_id'] . '"class="btn text-white w-100" id="item_edit" style="background:rgb(19, 39, 67)">View Item</a>
                       </div>
                      </div>
                        ';
                } else {
                  echo '
                      <div class="card col-lg-3 mx-lg-4 my-lg-4  border-1 border-danger my-md-2">';
                  echo '<div class="card-header position-relative">
                              <h2 class="position-absolute">' . $row['product_name'] . '<span class="position-relative top-0 rounded-bottom bg-warning text-white p-lg-3 fs-5">₱' . number_format($row['product_price']) . '</span></h2>
                            
                       </div>';
                  echo '<img src="../uploads/' . $row['img_name'] . '" class="card-img-top p-sm-2">';
                  if ($row['availability'] == 'available') {
                    echo "<span class='rounded-pill bg-success text-center'>" . $row['availability'] . "</span>";
                  } else {
                    echo "<span class='rounded-pill bg-danger text-center'>" . $row['availability'] . "</span>";
                  }
                  echo '<div class="card-body rounded">
                             <p>' . $row['product_description'] . '</p>
                       </div>
                       <div class="card-footer">
                           <a href="viewitem.php?product_id=' . $row['product_id'] . '"class="btn text-white w-100" id="item_edit" style="background:rgb(19, 39, 67)">View Item</a>
                       </div>
                      </div>
                        ';
                }
              }
            } else {
              echo "<div class='row justify-content-center'><img src='../img/svg/Questions-amico.svg' class='w-50 h-50' ><h2 class='w-100 text-center'>Empty</h2></div>";
            }


            ?>
          </div>



          <!-- end -->
        </div>
        <div class="mx-lg-5 col-lg-4">

          <div class="product-details__availability">

            <div class="product-item__button mt-lg-30 mt-sm-25 mt-20 d-flex flex-wrap">
              <div class=" d-flex flex-wrap product-item__action align-items-center">

                <!--  -->



              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- modals -->


<div class="modal fade" id="additemmenu" tabindex="-1" role="dialog" aria-labelledby="additemmenu" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="additemmenu">Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data">
          <div class="row">
            <div class="form-group col-lg-6">
              <label for="a3" class="il-gray fs-14 fw-500 align-center mb-10">Name</label>
              <input type="text" class="form-control ih-medium ip-light radius-xs b-light px-15" name="name" placeholder="Enter Item Name">
            </div>


            <div class="form-group mb-20 col-md-6">
              <label for="status" class="mb-2">Status</label>
              <select class="form-control form-control ih-medium ip-light radius-xs b-light px-15" name="status">
                <option value="available">Available</option>
                <option value="not">Not Available</option>
              </select>
            </div>

          </div>


          <div class="col-md-12 px-md-3">
            <div class="form-group">
              <label for="a8" class="il-gray fs-14 fw-500 align-center mb-10">Images</label>
              <div class="dm-upload">
                <div class="dm-upload-avatar media-import dropzone-md-s">
                  <p class="color-light mt-0 fs-14">Drop files here to upload</p>
                </div>
                <div class="avatar-up">
                  <input type="file" name="images[]" class="upload-avatar-input" id="uploadInput" multiple accept="image/*">

                </div>
              </div>
            </div>
            <div class="dm-upload__file">
              <ul id="imageList">
                <!-- Uploaded image names will be displayed here -->
              </ul>
            </div>
          </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="close">Cancel</button>

        <button type="submit" class="btn btn-secondary" name="additems">Save</button>
        <!-- Additional buttons or actions can be added here -->
      </div>
      </form>
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


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="js/plugins.min.js"></script>
<script src="js/script.min.js"></script>


<script src="./js/sweetalert2.all.min.js"></script>



<script>
  const uploadedInput = document.getElementById('fileInput');
  const imgupload = document.getElementById('imgs');
  uploadedInput.addEventListener('change', function(event) {
    const fileUp = event.target.files[0];
    imgupload.src = URL.createObjectURL(fileUp);
  });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    var uploadInput = document.getElementById('uploadInput');
    var dropzone = document.querySelector('.dm-upload-avatar.media-import.dropzone-md-s');

    uploadInput.addEventListener('change', function() {
      var files = this.files;
      dropzone.innerHTML = ''; // Clear previous content

      for (var i = 0; i < files.length; i++) {
        var file = files[i];
        if (file.type.match('image.*')) {
          var reader = new FileReader();

          reader.onload = function(event) {
            var imgElement = document.createElement('img');
            imgElement.classList.add('uploaded-image');
            imgElement.src = event.target.result;
            imgElement.style.maxWidth = '300px';
            imgElement.style.maxHeight = '300px';
            dropzone.appendChild(imgElement);
          };

          reader.readAsDataURL(file);
        }
      }
    });
  });
</script>



</body>

</html>