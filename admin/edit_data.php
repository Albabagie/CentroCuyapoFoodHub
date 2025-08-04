<?php

include('../connection.php');
if (isset($_POST['addemployee'])) {
    $employee_name = $_POST['name'];
    $employee_type = $_POST['type'];
    $employee_status = $_POST['status'];
    $employee_passcode = $_POST['passcode'];
    $account_type = $_POST['account_type'];
    $category = $_POST['category'];


    $trimmedName = str_replace(' ', '', $employee_name);
    $employee_email = $trimmedName . '@foodHub.com';


    $esql = "INSERT INTO users (email,password,account_type,category) VALUES ('$employee_email','$employee_passcode','$account_type', '$category')";

    if ($conn->query($esql)) {
        $user_id = $conn->insert_id;

        $employee_data = "INSERT INTO employee (user_id,employee_name, employee_type,employee_status, employee_date) VALUES ('$user_id','$employee_name','$employee_type','$employee_status',NOW())";

        if ($conn->query($employee_data) == TRUE) {
            echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: "Employee Added",
                text: "Nice!",
                icon: "success"
            });
        });
    </script>';
            header('Location: employee.php');
        } else {
            echo '<script>
          document.addEventListener("DOMContentLoaded", function() {
              Swal.fire({
                  title: "Error Recording Employee Data.",
                  text: "Please Try Again.",
                  icon: "error"
              });
          });
      </script>';
        }
    } else {
        echo 'error';
    }
}


if (isset($_POST['edit_data_btn'])) {
    $employee_id = $_POST['employee_id'];

    $dataarray = [];

    $employee_data = "SELECT * FROM employee e LEFT JOIN users u ON e.user_id = u.user_id WHERE employee_id = '$employee_id'";
    $res = $conn->query($employee_data);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            array_push($dataarray, $row);
            header('content-type: application/json');
            echo json_encode($dataarray);
        }
    }
}

if (isset($_POST['save_data'])) {
    $user_id = $_POST['user_id'];
    $employee_id = $_POST['employee_id'];
    $employee_name = $_POST['name'];
    $employee_status = $_POST['status'];
    $employee_pos = $_POST['pos'];
    $employee_email = $_POST['email'];
    $employee_pass = $_POST['passcode'];


    $update_e = "UPDATE employee SET employee_name = '$employee_name', employee_status = '$employee_status', employee_type = '$employee_pos' WHERE employee_id = '$employee_id'";

    if ($conn->query($update_e)) {
        $udate_user = "UPDATE users SET email = '$employee_email', password = '$employee_pass' WHERE user_id = '$user_id'";
        if ($conn->query($udate_user)) {
            echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: "Employee Added",
                text: "Nice!",
                icon: "success"
            });
        });
    </script>';
            header('Location:employee.php');
        }
    } else {
        echo "<script>alert('Error');</script>";
    }
}

if (isset($_POST['update_data_sales'])) {
    $sql = "SELECT 
        DATE_FORMAT(payment_date, '%Y-%m-01') AS month_start, 
        SUM(paid_amount) AS total_amount
    FROM payment
    GROUP BY YEAR(month_start), MONTH(month_start);
    ";

    $result = $conn->query($sql);

    // Check if query returns results
    if ($result->num_rows > 0) {
        // Prepare insert statement
        $stmt = $conn->prepare("INSERT INTO sales (sales_date, sales_amount) VALUES (?, ?)");

        // Loop through each row from the result set
        while ($row = $result->fetch_assoc()) {
            $payment_date = $row["month_start"];
            $total_amount = $row["total_amount"];

            // Bind parameters and execute the insert statement
            $stmt->bind_param("sd", $payment_date, $total_amount);
            $stmt->execute();

            // echo "Inserted: Date: " . $payment_date . " - Total Amount: " . $total_amount . "<br>";
        }
        header('Location: index');
        // Close the prepared statement
        $stmt->close();
    } else {
        echo "0 results";
    }
}



if(isset($_POST["submit_data_term"])) {
    $term_policy = $_POST['terms_policy'];
    $date_updated =  date('Y-m-d H:i:s');

    if(!empty($term_policy)){
        $check = "SELECT date_created FROM policy_term";
        $res_check = $conn->query($check);
        $row_terms = $res_check->fetch_assoc();

        if(!empty($row_terms)){
            $date_created = $row_terms['date_created'];
            $sql = "INSERT INTO policy_term(date_updated, date_created, terms_policies) VALUES (?,?,?)";
            $res_sql = $conn->prepare($sql);
            $res_sql->bind_param('sss', $date_updated,$date_created, $term_policy);
        
            if($res_sql->execute()){
               
                echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "Policies and Terms added",
                        text: "keep up the good work",
                        icon: "success"
                    });
                });
            </script>';
                    header('Location:profile');
                    exit();
                }
            }else{
                $update = "INSERT INTO policy_term (terms_policies) VALUES ('$term_policy')";
                $update_res = $conn->query($update);
    
                if($update_res){
                    echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            title: "Policies and Terms Updated",
                            text: "keep up the good work",
                            icon: "success"
                        });
                    });
                </script>';
                        header('Location:profile');
                    }
                }
            }
        } 
  else {
        echo "<script>alert('Error');</script>";
    }   
   