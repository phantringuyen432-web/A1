<?php
// connect.php - kết nối MySQL
// $DB_HOST = 'localhost';   
// $DB_PORT = 3306;          
// $DB_USER = 'nguyen';
// $DB_PASS = '12345';          
// $DB_NAME = 'QuanLyDatVeSuKien';
$DB_HOST = "sql302.infinityfree.com";
$DB_USER = "if0_40202094";
$DB_PASS = "tringuyen1710"; // hoặc mật khẩu bạn đã đặt
$DB_NAME = "if0_40202094_QuanlyDatVeSuKien";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
