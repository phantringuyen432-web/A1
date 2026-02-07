<?php
ob_start();
session_start();
include "connect.php";

// Xử lý đăng nhập khi form gửi đi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $matkhau = $_POST['matkhau'];

    // Tìm user theo email
    $sql = "SELECT * FROM NguoiDung WHERE Email='$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Kiểm tra mật khẩu
        if (password_verify($matkhau, $user['MatKhau'])) {
            // Lưu SESSION
            $_SESSION['MaNguoiDung'] = $user['MaNguoiDung'];
            $_SESSION['HoTen'] = $user['HoTen'];
            $_SESSION['VaiTro'] = $user['VaiTro']; // Quan trọng để phân quyền

            //  Chuyển hướng theo vai trò
            if ($user['VaiTro'] === 'admin') {
                header("Location: http://localhost/admin_ticketbox/dashboard.php"); // Trang admin
            } else {
                header("Location: http://localhost/WEB/index.php"); //Trang người dùng
            }
            exit();
        } else {
            echo "<script>alert('❌ Sai mật khẩu!'); window.location.href='dangnhap.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('❌ Email không tồn tại!'); window.location.href='dangnhap.php';</script>";
        exit();
    }
}
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập</title>
  <link rel="stylesheet" href="dangnhap.css">
</head>
<body>
  <div class="login-container">
    <h2>Đăng nhập</h2>

    <!-- Form gửi dữ liệu tới chính file này -->
    <form action="" method="post">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Nhập email" required>
      </div>

      <div class="form-group">
        <label for="matkhau">Mật khẩu:</label>
        <input type="password" id="matkhau" name="matkhau" placeholder="Nhập mật khẩu" required>
      </div>

      <button type="submit" class="btn-login">Đăng nhập</button>
    </form>

    <p class="register-link">
      Chưa có tài khoản? <a href="dangky.php">Đăng ký</a>
    </p>
  </div>
</body>
</html>
