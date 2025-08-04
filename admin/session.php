<?php
 include ('../connection.php');
 
session_start();

if(isset($_SESSION['user_id'])) {
    $admin_id = $_SESSION['user_id'];

    $admin = "SELECT * FROM users WHERE user_id = $admin_id";
    $result_admin = mysqli_query($conn,$admin);

    if( $result_admin = mysqli_fetch_assoc($result_admin) ) {
        $user_type = $result_admin['account_type'];
        $admin_email = $result_admin['email'];
        $admin_password = $result_admin['password'];

        if($user_type == 'admin') {
            $admin_details = "SELECT * FROM admin WHERE user_id = $admin_id";
            $res_details = mysqli_query($conn,$admin_details);

            if($res_details = mysqli_fetch_assoc($res_details)) {
                $admin_name = $res_details['admin_name'];
                $admin_desc = $res_details['admin_description'];
            }
        }
    }
}else {
    header("Location: ../index.php");
    exit;
}