<?php
// Kiểm tra sự tồn tại của user_id trong session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id === null) {
    die("Không tìm thấy thông tin user_id. Vui lòng đăng nhập lại.");
}

// Truy vấn để lấy wallet_id
$sql = $con->prepare("SELECT * FROM wallet WHERE customer_id = ?");
$sql->bind_param("i", $user_id);
$sql->execute();
$result = $sql->get_result();

$wallet_id = null;
if ($row1 = $result->fetch_array()) {
    $wallet_id = $row1['id'];
} else {
    die("Không tìm thấy ví cho người dùng này.");
}

// Truy vấn để lấy balance
$sql = $con->prepare("SELECT * FROM wallet_details WHERE wallet_id = ?");
$sql->bind_param("i", $wallet_id);
$sql->execute();
$result = $sql->get_result();

$balance = null;
if ($row1 = $result->fetch_array()) {
    $balance = $row1['balance'];
} else {
    $balance = 0; // Nếu không có thông tin ví, gán số dư là 0
}

// Hiển thị số dư ví
echo "Số dư ví: " . $balance;
?>
