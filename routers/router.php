<?php
include '../includes/connect.php';

$success = false;
$username = $_POST['username'];
$password = $_POST['password'];

// Truy vấn cho tài khoản Administrator
$result = mysqli_query($con, "SELECT * FROM users WHERE username='$username' AND role='Administrator' AND deleted IS NULL;");
if ($row = mysqli_fetch_array($result)) {
    // Kiểm tra mật khẩu
    if (password_verify($password, $row['password'])) {
        $success = true;
        $user_id = $row['id'];
        $name = $row['name'];
        $role = $row['role'];
    }
}

if ($success == true) {
    // Lưu thông tin vào session cho Administrator
    session_start();
    $_SESSION['admin_sid'] = session_id();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['role'] = $role;
    $_SESSION['name'] = $name;

    header("location: ../admin-page.php");
} else {
    // Truy vấn cho tài khoản Customer
    $result = mysqli_query($con, "SELECT * FROM users WHERE username='$username' AND role='Customer' AND deleted IS NULL;");
    if ($row = mysqli_fetch_array($result)) {
        // Kiểm tra mật khẩu
        if (password_verify($password, $row['password'])) {
            $success = true;
            $user_id = $row['id'];
            $name = $row['name'];
            $role = $row['role'];
        }
    }

    if ($success == true) {
        // Lưu thông tin vào session cho Customer
        session_start();
        $_SESSION['customer_sid'] = session_id();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;
        $_SESSION['name'] = $name;

        header("location: ../index.php");
    } else {
        // Nếu không tìm thấy tài khoản hợp lệ
        header("location: ../login.php");
    }
}
?>
