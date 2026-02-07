<?php
session_start();
include 'connect.php';

//  Kiểm tra đăng nhập
if (!isset($_SESSION['MaNguoiDung'])) {
    echo "<script>
        alert('⚠️ Bạn cần đăng nhập để thêm vé!');
        window.location.href='../dangnhap.php';
    </script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['su_kien_id'])) {
    $MaSuKien = intval($_POST['su_kien_id']);
    $MaNguoiDung = $_SESSION['MaNguoiDung'];

    //  Kiểm tra xem vé này đã tồn tại trong trạng thái CHƯA THANH TOÁN chưa
    $check_sql = "SELECT MaVe, SoLuong 
                  FROM Ve 
                  WHERE MaSuKien = '$MaSuKien' 
                  AND MaNguoiDung = '$MaNguoiDung' 
                  AND TrangThai = 'chuathanhtoan'
                  LIMIT 1";
    $check = $conn->query($check_sql);

    if ($check && $check->num_rows > 0) {
        //  Nếu đã có -> tăng số lượng
        $row = $check->fetch_assoc();
        $newSoLuong = $row['SoLuong'] + 1;
        $conn->query("UPDATE Ve SET SoLuong = '$newSoLuong' WHERE MaVe = '{$row['MaVe']}'");
    } else {
        //  Nếu chưa có -> thêm mới với số lượng mặc định = 1
        $sql = "INSERT INTO Ve (MaSuKien, MaNguoiDung, Gia, SoLuong, TrangThai) 
                SELECT MaSuKien, '$MaNguoiDung', GiaMacDinh, 1, 'chuathanhtoan'
                FROM SuKien
                WHERE MaSuKien = '$MaSuKien'
                LIMIT 1";
        $conn->query($sql);
    }

    echo "<script>
        alert(' Vé đã được thêm vào vé của bạn!');
        window.history.back();
    </script>";
} else {
    header("Location: ../index.php");
    exit;
}
?>
