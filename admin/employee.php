<?php
include('sidebar.php');

?>
<div class="contents" style="background: rgb(252,250,241)">
  <div class="container-fluid">
    <div class="social-dash-wrap">
      <div class="row">
        <div class="col-lg-12 px-4">
          <div class="breadcrumb-main">
            <b class="text-capitalize breadcrumb-title">Manage Employee</b>
            <div class="breadcrumb-action justify-content-center flex-wrap">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Dashboard
                    </a></li>
                  <li class="breadcrumb-item active" aria-current="page">Manage Employee</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 px-4">
          <div class="card my-md-3" style="background:rgb(248,239,212)">
            <div class="card-body">
              <div class="userDatatable adv-table-table global-shadow border-light-0 w-100 adv-table
              ">
                <div class="table-responsive">
                  <div class="adv-table-table__header">
                    <h4>List of Employee</h4>
                  </div>
                  <div class="mx-1 my-2 d-flex justify-content-end w-full">
                    <button type="button" class="btn text-white" data-toggle="modal" data-target="#exampleModal" style="background:rgb(19, 39, 67)"><i class="uil uil-plus"></i>Add Employee</button>
                  </div>
                  <!--  -->
                  <div id="filter-form-container"></div>
                  
                  <table class="table mb-0 table-borderless adv-table1" data-filter-container="#filter-form-container" data-paging-current="1" data-paging-position="right" data-paging-size="5">
                    <thead>
                      <tr class="userDatatable-header">
                        <th>
                          <span class="userDatatable-title">Name</span>
                        </th>
                        <th>
                          <span class="userDatatable-title">Designation</span>
                        </th>
                        <th data-type="html" data-name="Position">
                          <span class="userDatatable-title">Status</span>
                        </th>
                        <th>
                          <span class="userDatatable-title">Employee Number</span>
                        </th>

                        <th>
                          <span class="userDatatable-title">Employee Passcode</span>
                        </th>

                        <th>
                          <span class="userDatatable-title">date</span>
                        </th>
                        <th>
                          <span class="userDatatable-title text-center ">action</span>
                        </th>
                        <th>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                      // SQL query
                      $sql = "SELECT * FROM employee e LEFT JOIN users u ON e.user_id = u.user_id";
                      $result = $conn->query($sql);

                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                          echo "<tr>";
                          echo "<td class='d-none employee_id'>" . $row["employee_id"] . "</td>";
                          echo "<td><div class='userDatatable-content'>" . $row["employee_name"] . "</div></td>";
                          echo "<td><div class='d-flex'><div class='userDatatable-inline-title'><a href='#' class='text-dark fw-500'><h6>" . $row["employee_type"] . "</h6></a></div></div></td>";
                          echo "<td><div class='userDatatable-content'>" . $row["employee_status"] . "</div></td>";
                          echo "<td><div class='userDatatable-content'>" . $row["email"] . "</div></td>";
                          echo "<td><div class='userDatatable-content d-flex align-items-center position-relative'>
        <input type='password' disabled class='form-control border-0 bg-none' value='" . $row["password"] . "'>
        <i onclick='fieldPass(this)' class='uil uil-eye'></i>
        </div></td>";

                          echo "<td><div class='userDatatable-content'>" . $row["employee_date"] . "</div></td>";

                          echo "<td>
                            <button type='button' class='btn text-white' data-bs-toggle='modal'
                              data-bs-target='#reviewModal" . $row['employee_id'] . "' style='background:rgb(19, 39, 67)'><i class='uil uil-pen'></i>
                              Edit
                            </button>
                          </td>";



                          echo "</tr>";
                          echo '

                          <div class="modal fade" id="reviewModal' . $row["employee_id"] . '" tabindex="-1" aria-labelledby="reviewModal' . $row["employee_id"] . 'Label" aria-hidden="true">
                              <div class="modal-dialog">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title" id="reviewModal' . $row["employee_id"] . 'Label">Employee 11</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <form action="edit_data" method="post" enctype="multipart/form-data">   
                                    
                                          <div class="modal-body">
                                              <div class="new-member-modal">
                                                  <div class="form-group mb-20">
                                                  <input type="hidden" class="form-control" name="employee_id" value="' . $row["employee_id"] . '">
                                                  <input type="hidden" class="form-control" name="user_id" value="' . $row["user_id"] . '">
                                                      <label for="employee_name" class="il-gray fs-14 fw-500 align-center mb-10">Name</label>
                                                      <input type="text" class="form-control" name="name" value="' . $row['employee_name'] . '" required>

                                                  <label for="employee_designation" class="il-gray fs-14 fw-500 align-center mb-10 mt-10">Position</label>
                                                      <select name="pos" class="form-control" required>
                                                      <option vlaue"' . $row['employee_type'] . '" selected>' . $row['employee_type'] . '</option>
                                                      <option value"Waiter">Waiter</option>
                                                      <option value="Cashier">Cashier</option>
                                                      </select>
                                                    <label for="employee_status" class="il-gray fs-14 fw-500 align-center mb-10 mt-10">Status</label>
                                                      <select name="status" class="form-control" required>
                                                      <option selected disabled vlaue"' . $row['employee_status'] . '" selected>Current Status: ' . $row['employee_status'] . '</option>
                                                      <option value"active">Active</option>
                                                      <option value"out">Out</option>
                                                      </select>
                                                    <label for="employee_status" class="il-gray fs-14 fw-500 align-center mb-10 mt-10">Email / Employee ID</label>
                                                      <input type="text" class="form-control" name="email" value="' . $row['email'] . '" required>
                                                    <label for="employee_status" class="il-gray fs-14 fw-500 align-center mb-10 mt-10">Status</label>
                                                      <input type="text" class="form-control" name="passcode" value="' . $row['password'] . '" required>

                                                      
                                                  </div>
                                                  <div class="button-group d-flex d-flex-end pt-25">
                                                      <button type="submit" name="save_data" class="btn btn-primary btn-default btn-squared text-capitalize">Save</button>
                                                      <button type="button" class="btn btn-light btn-default btn-squared fw-400 text-capitalize b-light color-light" data-bs-dismiss="modal">Cancel</button>
                                                  </div>
                                              </div>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>
                          
                          ';
                          // Modal
                        }
                      } else {
                        echo "<div ><div colspan='4'>0 results</div></div>";
                      }
                      // $conn->close();

                      ?>

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- .add new -->
<div class=" modal fade" id="exampleModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-5">
        <form action="edit_data" method="POST">

          <div class="form-group mx-4 my-2">
            <label class="my-2" for="name"> <span class="title">Employee Name</span> </label>
            <input type="text" name="name" class="form-control py-2" placeholder="Juan Dela Cruz" required>

          </div>
          <div class="form-group mx-4 my-2">
            <label class="my-2" for="status"> <span class="title">Employee Status</span></label>
            <select class="form-control py-2" name="status" required>
              <option selected disabled>Select Status</option>
              <option value="Active">Active</option>
              <option value="Out">Out</option>
            </select>
          </div>
          <!-- Employee Type -->
<div class="form-group mx-4 my-2">
  <label class="my-2" for="type"> <span class="title">Employee Type</span></label>
  <select name="type" id="employee_type" class="form-control py-2" required onchange="toggleCategory()">
    <option selected disabled>Employee Role</option>
    <option value="Cook">Cook</option>
    <option value="Cashier">Cashier</option>
    <option value="stall">Stall Manager</option>
  </select>
</div>

<!-- Category Dropdown (Initially Hidden) -->
<div class="form-group mx-4 my-2" id="category_group" style="display: none;">
  <label class="my-sm-2" for="">Category</label>
  <select class="form-control w-100" name="category" id="product_list" onchange="addproducts()">
    <option selected disabled>Select Category</option>
    <?php
    $sql = "SELECT * FROM menu ";
    $result = $conn->query($sql);

    if ($result) {
      while ($row = $result->fetch_assoc()) {
    ?>
        <option value="<?php echo $row['category_id']; ?>"><?php echo $row['category']; ?></option>
    <?php
      }
    } else {
      echo '<option disabled>No products found</option>';
    }
    ?>
  </select>
</div>


          <div class="form-group mx-4 mt-2">
            <label class="my-2" for="passcode"> <span class="title">Passcode</span></label>
            <input type="password" name="passcode" class="form-control py-2" required>

          </div>
          <input type="hidden" value="employee" name="account_type" class="form-control py-2">

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-secondary" name="addemployee" data-toggle="modal" data-target="#exampleModal2">Add Employee</button>
        <!-- Additional buttons or actions can be added here -->
      </div>
      </form>
    </div>
  </div>
</div>

<!--  -->



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

<script src="js/plugins.min.js"></script>
<script src="js/script.min.js"></script>

<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="./js/sweetalert2.all.min.js"></script>
<script>
function toggleCategory() {
  const employeeType = document.getElementById('employee_type').value;
  const categoryGroup = document.getElementById('category_group');

  if (employeeType === 'stall') {
    categoryGroup.style.display = 'block';
  } else {
    categoryGroup.style.display = 'none';
  }
}

// Optional: Initialize visibility on page load if form is prefilled
document.addEventListener('DOMContentLoaded', toggleCategory);
</script>

<script>
  $((function() {
      $(".adv-table1").footable({
        filtering: {
          enabled: !0
        },
        paging: {
          enabled: !0,
          current: 1
        },
        strings: {
          enabled: !1
        },
        filtering: {
          enabled: !0
        },
        components: {
          filtering: FooTable.MyFiltering
        }
      })
    })),
    FooTable.MyFiltering = FooTable.Filtering.extend({
      construct: function(t) {
        this._super(t);
        this.jobTitles = ["Cashier", "Cook", "Waiter"];
        this.jobTitleDefault = "All";
        this.$jobTitle = null;
      },
      $create: function() {
        this._super();
        var t = this,
          s = $("<div/>", {
            class: "form-group dm-select d-flex align-items-center adv-table-searchs__status my-xl-25 my-15 mb-0 me-sm-30 me-0"
          }).append($("<label/>", {
            class: "d-flex align-items-center mb-sm-0 mb-2",
            text: "Status"
          })).prependTo(t.$form);
        t.$jobTitle = $("<select/>", {
          class: "form-control ms-sm-10 ms-0"
        }).on("change", {
          self: t
        }, t._onJobTitleDropdownChanged).append($("<option/>", {
          text: t.jobTitleDefault
        })).appendTo(s);
        $.each(t.jobTitles, (function(e, s) {
          t.$jobTitle.append($("<option/>").text(s));
        }));
      },
      _onJobTitleDropdownChanged: function(t) {
        var e = t.data.self,
          s = $(this).val();
        s !== e.jobTitleDefault ? e.addFilter("Position", s, ["Position"]) : e.removeFilter("Position");
        e.filter();
      },
      draw: function() {
        this._super();
        var e = this.find("Position");
        e instanceof FooTable.Filter ? this.$jobTitle.val(e.query.val()) : this.$jobTitle.val(this.jobTitleDefault);
      }
    });
</script>

<script>
  $(document).ready(function() {
    $('.edit_data').click(function(e) {
      e.preventDefault();
      var
        employee_id = $(this).closest('tr').find('.employee_id').text();
      $.ajax({
        method: "POST",
        url: "edit_data",
        data: {
          'edit_data_btn': true,
          'employee_id': employee_id,
        },
        success: function(response) {
          console.log(response);
          $.each(response, function(key, value) {
            $('#e_id').val(value['employee_id']);
            $('#ename').val(value['employee_name']);
            $('#estatus').val(value['employee_status']);
            $('#epos').val(value['employee_type']);
            $('#epass').val(value['password']);
          })
        }
      })
    })
  })
</script>
<script>
  function fieldPass(button) {
    var inputField = button.previousElementSibling;
    if (inputField.type === 'password') {
      inputField.type = 'text';
      button.classList.remove('uil-eye-slash');
      button.classList.add('uil-eye');
    } else {
      inputField.type = 'password';
      button.classList.remove('uil-eye');
      button.classList.add('uil-eye-slash');
    }
  }
</script>

<script>
  function editHide() {
    const editpass = document.getElementById('epass');
    const hidepass = document.getElementById('hide_pass');

    if (editpass.type === 'password') {
      editpass.type = 'text';
      hidepass.classList.remove('uil-eye-slash');
      hidepass.classList.add('uil-eye');
    } else {
      editpass.type = 'password';
      hidepass.classList.remove('uil-eye');
      hidepass.classList.add('uil-eye-slash');

    }
  }
</script>

</body>

</html>