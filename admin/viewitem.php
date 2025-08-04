<?php
include('sidebar.php');
   $product_id = $_GET['product_id'];
?>
<?php
if(isset($_POST['update_item'])){
  $item_desc = $_POST['item_description'];
  $item_price = $_POST['item_price'];
  $item_status = $_POST['status'];
  $item_id = $_POST['item_id'];

  $update_details = "UPDATE inventory SET product_description = '$item_desc', product_price = '$item_price', state = '$item_status' WHERE product_id = ' $item_id'";
  $result_updated = $conn->query($update_details);

  if($result_updated){
          if (!empty($_FILES["images"]["name"])) {
            $targetDir = "../uploads/";
            $fileName = basename($_FILES["images"]["name"]);
            $targetFilePath = $targetDir.$fileName;
  
            // Upload the new image
            if (move_uploaded_file($_FILES["images"]["tmp_name"], $targetFilePath)) {
                $image_sql = "UPDATE product_img SET img_name=? WHERE product_id=?";
                $imageStmt = $conn->prepare($image_sql);
                $imageStmt->bind_param("si", $targetFilePath, $item_id);
                if ($imageStmt->execute()) {
                    echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                      Swal.fire({
                          title: "Item Updated Successfully!",
                          text: "Item is Updated.",
                          icon: "success"
                      })..then(function() {
                        window.location.href = "items.php"; 
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
}

$sql = "SELECT * FROM inventory i LEFT JOIN product_img p ON p.product_id = i.product_id WHERE i.product_id = '$product_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
    
        $item_id = $row["product_id"];
        $item_name = $row["product_name"];
        $item_price = $row["product_price"];
        $item_description = $row["product_description"];
        $item_img = $row["img_name"];
        $item_status = $row["state"];
       
       
    }
} else {
    echo "0 results";
}

?>

  <div class="contents" style="background:rgb(252,250,241)">
        <div class="container-fluid ">
          <div class="row">
            <div class="col-lg-12">
              <div class="shop-breadcrumb">
                <div class="breadcrumb-main px-4">
                  <h4 class="text-capitalize breadcrumb-title">Item</h4>
                  <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">See item</li>
                      </ol>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="products mb-3 px-3">
          <div class="container-fluid">
            <div class="card product-details h-100 border-0" style="background:rgb(248,239,212);">
              <div class="product-item p-sm-50 p-20">
                <div class="row">
                  <!-- <div class="d-flex flex-column jutify-content-around "> -->
                  <div class="col-lg-4 mb-lg-2">
                    <div class="product-item__image">
                    <div class="wrap-gallery-article carousel slide carousel-fade" id="carouselExampleCaptions" data-bs-ride="carousel">
                        <div>
                        <?php
// Assuming $last_id contains the listing ID
$imageQuery = "SELECT img_name FROM product_img WHERE product_id = ?";
$imageStmt = $conn->prepare($imageQuery);
$imageStmt->bind_param("i", $product_id);
$imageStmt->execute();
$imageResult = $imageStmt->get_result();

$imageUrls = array();

while ($row = $imageResult->fetch_assoc()) {
    $imageUrls[] = $row['img_name'];
}

$imageStmt->close();
?>

<div class="carousel-inner mx-lg-4">
    <?php foreach ($imageUrls as $index => $item_img): ?>
        <div class="carousel-item
        <?php if($index === 0){
          echo 'active';
        } else {
          echo '';
        }
        ?>">
            <img class="img-fluid d-flex bg-opacity-primary" src="../uploads/<?php echo $item_img; ?>" alt="Card image cap" title="">
        </div>
    <?php endforeach; ?>
</div>

                        </div>
                        <div class="overflow-hidden">
                       <?php
                $imageQuery = "SELECT img_name FROM product_img WHERE product_id = ?";
                $imageStmt = $conn->prepare($imageQuery);
                $imageStmt->bind_param("i", $product_id);
                $imageStmt->execute();
                $imageResult = $imageStmt->get_result();

                $imageUrls = array();

                while ($row = $imageResult->fetch_assoc()) {
                    $imageUrls[] = $row['img_name'];
                }


?>

                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="mx-lg-3 col-lg-6">
                    <div class=" b-normal-b mb-25 pb-sm-35 pb-15 mt-lg-0 mt-15">
                      <div class="product-item__body">
                        <div class="product-item__title">
                            <h1 class="card-title text-decoration-underline">
                            <?php echo $item_name;?> <span>
              <?php 
              if($item_status == 0){
                echo '<span class="font-size-sm badge bg-success text-white p-2 rounded-pill text-bg-success text-dark text-capitalize mx-4">Available</span>';

              } else {
                echo '<span class="font-size-sm badge bg-danger text-white p-2 rounded-pill text-bg-success text-dark text-capitalize mx-4">Not Available</span>';

              }
            
            ?></span>
                            </h1>
                        </div>
                        <div class="product-item__content text-capitalize">
                    <div class="product-item__content text-capitalize">
<div class="product-item__content text-capitalize">
    <div class="stars-rating d-flex align-items-center">
        <?php
            $sql_rating = "SELECT *, COUNT(review) AS count_review, AVG(ratings) AS Avg_rate FROM rating WHERE item_id = '$item_id' GROUP BY item_id";
            $reslt_rating = $conn->query($sql_rating);
            if($reslt_rating ){
              $rating = 0;
              $reviews = 0;
                while ($row = $reslt_rating->fetch_assoc()) {
                  $rating = $row['Avg_rate'];
                  $reviews = $row['count_review'];
            }
          }


        ?>
        <span class="stars-rating__point"> <?php echo number_format($rating,1) ?>
           <?php 
             $numStars = intval($rating);
             $decimal = $rating - $numStars;

             for ($i = 0; $i < 5; $i++) {
               if ($i < $numStars) {
                 echo '<span class="star-icon las la-star active"></span>';
               } else {
                 if ($decimal > 0) {
                   echo '<span class="star-icon las la-star-half-alt active"></span>';
                   $decimal = 0;
                 } else {
                   echo '<span class="star-icon las la-star"></span>';
                 }
               }
             }
           ?>
        </span>
        <span class="stars-rating__review">
            <span><?php echo $reviews?> Reviews</span> 
        </span>
        
    </div>
</div>
</div>
                          <span class="product-desc-price text-dark"> Price: 
                          <?php 
                                $sql_promo_e = "SELECT * FROM promo WHERE item_id = '$item_id' AND promo_status = 'active'";
                                $result_e = $conn->query($sql_promo_e);

                                if($result_e->num_rows > 0){
                                  while ($row = $result_e->fetch_assoc()) {
                                    
                                    echo  ' <span class="mx-1 py-1 px-1 text-white bg-warning rounded">'.number_format($row['item_current_price'],2).' php</span> <span class="mx-2 text-decoration-line-through text-danger">'. number_format($row['item_promo_price'],2).' php</span>'; 
                                   
                                  }
                                } else {
                                  echo '<span class="mx-2 ">'.number_format($item_price,2).' Php</span>';
                                }
                          ?>
                        </span>
                          <div class="d-flex align-items-center mb-2">
                          </div>
                          <p class=" product-deatils-pera"><?php echo $item_description;?></p>
                          <div class="product-details__availability">
                  <div class="title">
    <span style="color:red;">
    </span>
</div>
 <div class="title">
                      </div>
                          </div>
                     
                        
                        </div>
                      </div>
                    </div>
                    <div class="product-details__availability">
                   
                        <div class="product-item__button mt-lg-30 mt-sm-25 mt-20 d-flex flex-wrap">
                            <div class=" d-flex flex-wrap product-item__action align-items-center">
                 
              <button data-bs-toggle='modal' data-bs-target="#ownerModal<?php echo $item_id; ?>" class="btn text-white btn-squared border-0 me-10 my-sm-0 my-2" style="background:rgb(19, 39, 67)">Edit Item</button>
                            </div>
                           
 
                            <div class='modal fade' id="ownerModal<?php echo $item_id; ?>" tabindex='-1' role='dialog' aria-labelledby='ownerModalLabel' aria-hidden='true' data-backdrop='static'>
     <div class='modal-dialog modal-dialog-centered' role="document">
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='ownerModalLabel'><?= $item_name;?></h5>

               
                <button type='button' class='close' data-bs-dismiss='modal' aria-label='Close'>
                    <span class="text-dark" aria-hidden='true'>x</span>
                </button>
            </div>
            <div class='modal-body'>
              <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="item_id" value="<?php echo $item_id;?>">
                <div class="d-flex flex-row justify-content-center align-items-center my-5">
                <div class="img-hover w-50">
                  <img id="imgs" src="../uploads/<?php echo $item_img; ?>" alt="x" class="card-img-top">
                  <span onclick="document.getElementById('fileInput').click();" class="hover-text-edit">Change Image</span>
                </div>
                <div class="form-group">
                  <input class="hover-text-edit" name="images" type="file" style="display:none;" id="fileInput" multiple accept="image/*">
                </div>
              </div>

            <div class="row mx-auto" >
            <div class="form-group mb-20 col-md-4">
                    <label for="gender" class="mb-2">Status</label>
                    <select class="form-control form-control ih-medium ip-light radius-xs b-light px-15" name="status">
                      
                        <option value="0">Available</option>
                        <option value="1">Not Available</option>
                    </select>
                </div>
            <div class="form-group w-50 col-md-4">
                    <label for="" class="mb-2">Item Price</label>
                    <?php
                     $sql_promo_e = "SELECT * FROM promo WHERE item_id = '$item_id' AND promo_status = 'active'";
                     $result_e = $conn->query($sql_promo_e);

                     if($result_e->num_rows > 0){
                       while ($row = $result_e->fetch_assoc()) {
                        echo '<input type="number" class="form-control form-control ih-medium ip-light radius-xs b-light px-15" name="item_price" value="'.$row['item_current_price'].'" readonly>';
                        echo '<span class="text-danger">*currently active promo.</span>';
                        }
                       } else{
                        echo '<input type="number" class="form-control form-control ih-medium ip-light radius-xs b-light px-15" name="item_price" value="'.$item_price.'" >';
                       }
                        ?>
                </div>
            
                <div class="form-group w-75 ">
                    <label for="">Item Description</label>
                    <textarea class="form-control font-size-sm" name="item_description" ><?=$item_description;?></textarea>
                </div>
                
              </div>
            </div>
            
            <div class='modal-footer'>
                <button type='submit' class='btn btn-secondary' name="update_item" data-bs-dismiss='modal'>Update</button>
            </div>
            </form>
        </div>
    </div>
</div>

                          </div>
                    </div>
                  </div>
                  <div class="my-4 text-center">
                       <h2> Feedback and Rating</h2>
                   </div>
                   <hr>
                   
        <div class="row">
          <!-- <div class="container-fluid rounded bg-danger"> -->
            <?php 
            $sql_reviews = "SELECT * FROM rating JOIN customer ON rating.customer_id = customer.customer_id where rating.item_id = '$item_id'";
            $res_reviews = $conn->query($sql_reviews);

            if($res_reviews->num_rows > 0){
              while($row_review = $res_reviews->fetch_assoc()) {
                ?>
              <div class="col-md-4 col-md-4 mb-25">
                <div class="card">
                  <div class="user-group px-30 pt-30 pb-25 radius-xl">
                    <div class="border-bottom">
                      <div class="media user-group-media d-flex justify-content-between">
                        <div class="media-body d-flex align-items-center">
                          <img class="me-20 wh-70 rounded-circle bg-opacity-primary" src="img/user.png" alt="author">
                          <div>
                            <h6 class="mt-0  fw-500"><?php echo $row_review['name'] ?></h6>
                          </div>
                        </div>
                        <div class="mt-n15"></div>
                      </div>
                      <p class="mt-15"><?php 
                      if(!empty($row_review['review']) || $row_review['review'] != NULL){
                        echo $row_review['review'];
                      } else {
                        echo '<div class=""></div>';
                      }
                     
                      ?></p>
                    </div>
                    <div class="stars-rating align-items-center">
                      <?php
                      $rating = $row_review['ratings'];
                      $numStars = intval($rating);
                      $decimal = $rating - $numStars;

                      for ($i = 0; $i < 5; $i++) {
                        if ($i < $numStars) {
                          echo '<span class="star-icon las la-star active"></span>';
                        } else {
                          if ($decimal > 0) {
                            echo '<span class="star-icon las la-star-half-alt active"></span>';
                            $decimal = 0;
                          } else {
                            echo '<span class="star-icon las la-star"></span>';
                          }
                        }
                      }
                      ?>
                      <span class="stars-rating__point">
                        <?php 
                        // echo $rating == intval($rating) ? number_format($rating, 0) : number_format($rating, 1); 
                        if( $rating == intval($rating)){
                          echo number_format($rating, 0);
                        } else {
                          echo number_format($rating, 1); 
                        }
                        ?>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
                <?php
              }
            } else {
              echo '<div class="container text-center">
              <span class="text-dark">No Rating</span>
              </div>';
            }
            ?>

<!-- </div> -->
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
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="./js/sweetalert2.all.min.js"></script>

<script>
const uploadedInput = document.getElementById('fileInput'); 
const imgupload = document.getElementById('imgs'); 

uploadedInput.addEventListener('change', function(event) { 
    const fileUp = event.target.files[0];

    if (fileUp) {
        // Validate that the file is an image
        if (fileUp.type.startsWith('image/')) {
            imgupload.src = URL.createObjectURL(fileUp);
        } else {
            alert("Please upload a valid image file.");
        }
    }
});

</script>

   <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      








</body>
</html>