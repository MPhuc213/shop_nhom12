<?php
include '../includes/connect.php';
$name = htmlspecialchars($_POST['name']);
$username = htmlspecialchars($_POST['username']);
$password = htmlspecialchars($_POST['password']);
$phone = htmlspecialchars($_POST['phone']);  // Đảm bảo số điện thoại cũng được làm sạch và kiểm tra

// Mã hóa mật khẩu trước khi lưu
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Hàm tạo số ngẫu nhiên (số thẻ và CVV)
function number($length) {
    $result = '';
    for($i = 0; $i < $length; $i++) {
        $result .= mt_rand(0, 9);
    }
    return $result;
}

// Sử dụng Prepared Statements để ngăn SQL Injection
$stmt = $con->prepare("INSERT INTO users (name, username, password, contact) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $username, $hashed_password, $phone);

if ($stmt->execute()) {
    $user_id = $con->insert_id;  // Lấy ID người dùng vừa thêm

    // Thêm ví vào cơ sở dữ liệu
    $stmt_wallet = $con->prepare("INSERT INTO wallet(customer_id) VALUES (?)");
    $stmt_wallet->bind_param("i", $user_id);

    if ($stmt_wallet->execute()) {
        $wallet_id = $con->insert_id;  // Lấy ID ví vừa thêm

        // Tạo số thẻ và CVV ngẫu nhiên
        $cc_number = number(16);
        $cvv_number = number(3);

        // Thêm thông tin thẻ vào ví
        $stmt_wallet_details = $con->prepare("INSERT INTO wallet_details(wallet_id, number, cvv) VALUES (?, ?, ?)");
        $stmt_wallet_details->bind_param("iss", $wallet_id, $cc_number, $cvv_number);
        $stmt_wallet_details->execute();
    }
    
    // Đóng các statement
    $stmt->close();
    $stmt_wallet->close();
    $stmt_wallet_details->close();
    
    header("Location: ../login.php");
    exit;
} else {
    echo "Lỗi: " . $stmt->error;
}
?>
