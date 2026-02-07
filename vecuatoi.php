<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['MaNguoiDung'])) {
    header("Location: dangnhap.php");
    exit;
}

$MaNguoiDung = $_SESSION['MaNguoiDung'];

$sql = "
SELECT Ve.MaVe, Ve.TrangThai, Ve.Gia, SuKien.TenSuKien, SuKien.HinhAnh, SuKien.NgayGio,
Ghe.DayGhe, Ghe.SoGhe
FROM Ve
JOIN SuKien ON Ve.MaSuKien = SuKien.MaSuKien
JOIN Ghe ON Ve.MaGhe = Ghe.MaGhe
WHERE Ve.MaNguoiDung = '$MaNguoiDung' AND Ve.TrangThai = 'dathanhtoan'
ORDER BY Ve.NgayDat DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Vé của tôi</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; }
.table { width: 90%; margin: 40px auto; border-collapse: collapse; background: white; }
.table th, .table td { border: 1px solid #ddd; padding: 10px; text-align: center; }
.table th { background: #2dc275; color: white; }
img { width: 100px; border-radius: 10px; }
.back-button {
    display: block;
    width: 20%;
    margin: 20px auto 0;
    padding: 10px 0;
    text-align: center;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}
.back-button:hover {
    background-color: #0056b3;
}
</style>
</head>
<body>
<h2 style="text-align:center; margin-top:30px;">🎟 Vé của tôi</h2>
<table class="table">
<tr>
    <th>Hình ảnh</th>
    <th>Sự kiện</th>
    <th>Thời gian</th>
    <th>Ghế</th>
    <th>Giá</th>
    <th>Trạng thái</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><img src="../img/<?php echo $row['HinhAnh']; ?>"></td>
    <td><?php echo $row['TenSuKien']; ?></td>
    <td><?php echo $row['NgayGio']; ?></td>
    <td><?php echo $row['DayGhe'] . $row['SoGhe']; ?></td>
    <td><?php echo number_format($row['Gia']); ?>đ</td>
    <td><span style="color:green;font-weight:bold;">✅ Đã thanh toán</span></td>
</tr>
<?php } ?>
</table>
<a href="../index.php" class="back-button">← Về trang chủ</a>
</body>
</html>
