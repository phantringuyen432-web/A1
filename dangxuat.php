<?php
//  Bắt đầu session
session_start();

//  Hủy session hiện tại
session_unset();
session_destroy();

//  Xóa cookie phiên nếu có
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

//  Chuyển hướng về trang chủ
header("Location: http://localhost/WEB/index.php");
exit();
