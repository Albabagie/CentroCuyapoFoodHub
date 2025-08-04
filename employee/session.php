<?php
include ('../connection.php');
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $sql_credentials = "SELECT * FROM users WHERE user_id = $user_id";
    $result_credentials = mysqli_query($conn, $sql_credentials);

    if ($row_credentials = mysqli_fetch_assoc($result_credentials)) {
        $user_type = $row_credentials['account_type'];
        $email = $row_credentials['email'];
        $password = $row_credentials['password'];
        if ($user_type === 'customer') {
            $sql_customer = "SELECT * FROM customer WHERE user_id = $user_id";
            $result_customer = mysqli_query($conn, $sql_customer);

            if ($row_customer = mysqli_fetch_assoc($result_customer)) {
                $name = $row_customer['name'];
                $birthdate = $row_customer['birthdate'];
                $gender = $row_customer['gender'];
                $id = $row_customer['customer_id'];
            }
        } elseif ($user_type === 'employee') {
            $sql_owner = "SELECT * FROM employee WHERE user_id = $user_id";
            $result_owner = mysqli_query($conn, $sql_owner);

            if ($row_owner = mysqli_fetch_assoc($result_owner)) {
                $name = $row_owner['employee_name'];
                $employee_status = $row_owner['employee_status'];
                $employee_type = $row_owner['employee_type'];
                $id = $row_owner['employee_id'];

                if(!empty($id) || $id != NULL){
                        $sql = "SELECT * FROM employee_photo WHERE employee_id = $user_id";
                        $result_img = mysqli_query($conn, $sql);

                        if($result_row = mysqli_fetch_assoc( $result_img)){
                            $image_path = $result_row['photo_path'];
                        }
                }
            }
        }
    }
} else {
    header("Location: ../index.php");
    exit;
}