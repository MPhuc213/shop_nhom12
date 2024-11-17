<?php
session_start();

// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost";
$server_user = "root";
$server_pass = "";
$dbname = "Food";

// Kiểm tra sự tồn tại của các phần tử trong $_SESSION
$name = isset($_SESSION['name']) ? $_SESSION['name'] : null;
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Kết nối đến cơ sở dữ liệu
$con = new mysqli($servername, $server_user, $server_pass, $dbname);

// Kiểm tra kết nối
if ($con->connect_error) {
    die("Kết nối thất bại: " . $con->connect_error);
}
?>
