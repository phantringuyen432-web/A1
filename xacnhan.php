<?php
session_start();
include 'connect.php';

//  Kiểm tra đăng nhập
if (!isset($_SESSION['MaNguoiDung'])) {
    echo "<script>alert('⚠️ Bạn cần đăng nhập để xác nhận thanh toán!'); window.location.href='dangnhap.php';</script>";
    exit;
}

$MaNguoiDung = $_SESSION['MaNguoiDung'];
$MaSuKien = $_POST['MaSuKien'] ?? null;

// Lấy danh sách ghế người dùng chọn (nếu có)
$ds_ghe_json = $_POST['ghe_chon'] ?? '[]';
$ds_ghe = json_decode($ds_ghe_json, true);

if (!$MaSuKien) {
    echo "<script>alert('⚠️ Thiếu thông tin sự kiện!'); window.location.href='vecuatoi.php';</script>";
    exit;
}

/*  Thêm vé mới cho các ghế người dùng chọn (nếu chưa có) */
if (!empty($ds_ghe)) {
    foreach ($ds_ghe as $gheCode) {
        $day = preg_replace('/[^A-Z]/i', '', $gheCode);
        $so = preg_replace('/[^0-9]/', '', $gheCode);

        // Lấy thông tin ghế
        $sql_ghe = "SELECT MaGhe, Gia FROM Ghe 
                    WHERE MaSuKien='$MaSuKien' AND DayGhe='$day' AND SoGhe='$so' LIMIT 1";
        $result_ghe = $conn->query($sql_ghe);

        if ($result_ghe && $row_ghe = $result_ghe->fetch_assoc()) {
            $MaGhe = $row_ghe['MaGhe'];
            $Gia = $row_ghe['Gia'];

            // Kiểm tra vé đã tồn tại chưa
            $check = $conn->query("SELECT * FROM Ve WHERE MaNguoiDung='$MaNguoiDung' AND MaGhe='$MaGhe'");
            if ($check->num_rows == 0) {
                // Thêm mới vé với trạng thái chưa thanh toán
                $conn->query("INSERT INTO Ve (MaSuKien, MaNguoiDung, MaGhe, Gia, SoTienThanhToan, TrangThai)
                              VALUES ('$MaSuKien', '$MaNguoiDung', '$MaGhe', '$Gia', '$Gia', 'chuathanhtoan')");
            }
        }
    }
}

/*  Cập nhật vé sang trạng thái đã thanh toán */
$sql_update_ve = "
    UPDATE Ve
    SET TrangThai = 'dathanhtoan',
        NgayThanhToan = NOW()
    WHERE MaNguoiDung = '$MaNguoiDung'
      AND MaSuKien = '$MaSuKien'
      AND TrangThai = 'chuathanhtoan'
";
$conn->query($sql_update_ve);

/*  Cập nhật trạng thái ghế sang 'đã đặt' */
$sql_update_ghe = "
    UPDATE Ghe
    JOIN Ve ON Ghe.MaGhe = Ve.MaGhe
    SET Ghe.TrangThai = 'dat'
    WHERE Ve.MaNguoiDung = '$MaNguoiDung'
      AND Ve.MaSuKien = '$MaSuKien'
      AND Ve.TrangThai = 'dathanhtoan'
";
$conn->query($sql_update_ghe);

/*  Thông báo & chuyển hướng */
echo "<script>
    alert('🎉 Thanh toán thành công! Mỗi vé đã được lưu với giá riêng và ghế cập nhật trạng thái ĐÃ ĐẶT.');
    window.location.href='vecuatoi.php';
</script>";
exit;
?>
