<?php
include('sidebar.php');
$category_id = $_GET['category_id'];
?>
<?php

   if(isset($_POST['updatestore'])) {  
      $stall_name = $_POST['name'];
      $stall_status = $_POST['status'];
      $stall_description = $_POST['description'];

      $update_data = "UPDATE menu SET category = ?, status = ?, stall_description = ? WHERE category_id = ?";
      $update_res = $conn->prepare($update_data);
      $update_res->bind_param("sssi", $stall_name, $stall_status, $stall_description, $category_id);
      $update_res->execute();
      
      // Check if a new image is uploaded
      if (!empty($_FILES["images"]["name"])) {
          $targetDir = "../uploads/";
          $fileName = basename($_FILES["images"]["name"]);
          $targetFilePath = $targetDir.$fileName;
  
          // Upload the new image
          if (move_uploaded_file($_FILES["images"]["tmp_name"], $targetFilePath)) {
              $image_sql = "UPDATE menu SET image_stall=? WHERE category_id=?";
              $imageStmt = $conn->prepare($image_sql);
              $imageStmt->bind_param("si", $targetFilePath, $category_id);
              if ($imageStmt->execute()) {
                  echo '<script>
                  document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "Item Updated Successfully!",
                        text: "Item is Updated.",
                        icon: "success"
                    })..then(function() {
                      window.location.href = "stalls.php"; 
                      exit();
                    });
                });
                  </script>';
  
              } else {
                  echo '<script>alert("Failed to update image in the database");</script>';
              }
              $imageStmt->close();
          } else {
              echo '<script>alert("Failed to upload image file");</script>';
          }
          
      } 
  }
   

$sql_stall = "SELECT * FROM menu WHERE category_id = '$category_id'";
$res_stall = $conn->query($sql_stall);
if($res_stall->num_rows > 0 ){
  while($row = $res_stall->fetch_assoc()){
        $stall_name = $row['category'];
        $stall_status = $row['status'];
        $stall_img = $row['image_stall'];
        $stall_desc = $row['stall_description'];
  }
}



?>

<div class="contents" style="background:rgb(252,250,241)">
  <div class="container-fluid">
    <div class="social-dash-wrap">
      <div class="row">
        <div class="col-lg-12">
          <div class="breadcrumb-main px-3">
            <h4 class="text-capitalize breadcrumb-title">Edit Stall</h4>
            <div class="breadcrumb-action justify-content-center flex-wrap">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Dashboard
                  </a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit Stall</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-md-4 px-3">
        <div class="col-md-12 ">
          <div class="card my-md-4">
            <div class="card-header">
              <h6>Item</h6>
            </div>
            <div class="card-body">
              <div class="card card-default card-md mb-4">
                <div class="card-body py-md-25">
                 <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-lg-6">
                          <label for="a3" class="il-gray fs-14 fw-500 align-center mb-10">Name</label>
                          <input type="text" class="form-control ih-medium ip-light radius-xs b-light px-15" name="name" placeholder="Enter Item Name" value="<?php echo $stall_name; ?>"  required>
                      </div>
                      
                        
                      
                      <div class="form-group mb-20 col-md-6">
                    <label for="status" class="mb-2">Status</label>
                    <select class="form-control form-control ih-medium ip-light radius-xs b-light px-15" name="status" required>
                        <option selected disabled>Select Status</option>
                        <option value="available">Available</option>
                        <option value="not">Not Available</option>
                    </select>
                </div>
        <div class="form-group">
          <label for="description" class="mb-2">Description</label>
          <input type="text" class="form-control ih-medium ip-light radius-xs b-light px-15" name="description" value="<?= $stall_desc;?>" required>
        </div>
          </div>


        </div>
                    <div class="col-md-12 px-md-3 ">
          <div class="form-group">
            <label for="a8" class="il-gray fs-14 fw-500 align-center mb-10">Images</label>
            <div class="dm-upload">
              <div class="dm-upload-avatar media-import dropzone-md-s ">
              <img src="../uploads/<?php echo $stall_img; ?>" class="w-25 h-25">
              </div>
              <div class="avatar-up">
        <input type="file" name="images" class="upload-avatar-input" id="uploadInput" multiple accept="image/*" value="../uploads/<?php echo $stall_img;?>">

              </div>
            </div>
          </div>
          <div class="dm-upload__file">
            <ul id="imageList">
              <!-- Uploaded image names will be displayed here -->
            </ul>
          </div>
        </div>



                              
        <hr style="margin: 5px 0; padding: 0;">

                            <div class="row">
                              <div class="col-md-12 d-flex justify-content-center align-items-center mt-md-2">
                                <div class="form-group w-50">
                                  <button type="submit" name="updatestore" class="btn text-white w-100" style="background:rgb(19, 39, 67)">Update</button>
                                </div>
                              </div>
                            </div>


                          </form>
                        </div>
                      </div>

                    </div>
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
     
<script src="js/plugins.min.js"></script>
<script src="js/script.min.js"></script>
<script scr="./js/sweetalert2.all.min.js"></script>


<script>
  document.getElementById('uploadInput').addEventListener('change', handleFileSelect, false);

  function handleFileSelect(event) {
    const files = event.target.files;
    const list = document.getElementById('imageList');
    list.innerHTML = '';
    for (let i = 0; i < Math.min(files.length, 5); i++) {
      const file = files[i];
      const listItem = document.createElement('li');
      const fileName = document.createElement('span');
      fileName.textContent = file.name;
      const deleteBtn = document.createElement('a');
      deleteBtn.className = 'btn-delete';
      deleteBtn.innerHTML = '<i class="la la-trash"></i>';
      deleteBtn.addEventListener('click', function () {
        listItem.remove();
      });
      listItem.appendChild(fileName);
      listItem.appendChild(deleteBtn);
      list.appendChild(listItem);
    }
  }
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



   <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        

        
</body>
</html>