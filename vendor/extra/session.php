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
            $sql_tenant = "SELECT * FROM customer WHERE user_id = $user_id";
            $result_tenant = mysqli_query($conn, $sql_tenant);

            if ($row_tenant = mysqli_fetch_assoc($result_tenant)) {
                $name = $row_tenant['name'];
                $birthdate = $row_tenant['birthdate'];
                $gender = $row_tenant['gender'];
                $id = $row_tenant['customer_id'];

            }
        } elseif ($user_type === 'employee') {
            $sql_owner = "SELECT * FROM employee WHERE user_id = $user_id";
            $result_owner = mysqli_query($conn, $sql_owner);

            if ($row_owner = mysqli_fetch_assoc($result_owner)) {
                $name = $row_owner['name'];
                $birthdate = $row_owner['birthdate'];
                $gender = $row_owner['gender'];
                $id = $row_owner['owner_id'];


            }
        }
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>