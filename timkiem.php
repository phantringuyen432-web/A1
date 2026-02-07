<?php
include __DIR__ . '/connect.php';

if (isset($_GET['keyword'])) {
    $keyword = trim($_GET['keyword']);
    if ($keyword != '') {
        //  Sửa tên bảng thành SuKien theo CSDL mới
        $sql = "SELECT * FROM SuKien WHERE TenSuKien LIKE ?";
        $stmt = $conn->prepare($sql);
        $like = "%$keyword%";
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $mask=$row['MaSuKien']; $gia= mysqli_fetch_array(mysqli_query($conn,"select min(gia) as MIN from Ghe where MaSuKien='$mask'"));
                echo '<div class="event">';
                echo '<img src="img/' . htmlspecialchars($row['HinhAnh']) . '" class="event-img">';
                echo '<div class="event-text">';
                echo '<h3 class="name">' . htmlspecialchars($row['TenSuKien']) . '</h3>';
                echo '<p class="cost">Từ ' . number_format($gia['MIN']) . 'đ</p>';
                echo '<p class="date"><i class="ti-calendar"></i> ' . date("d", strtotime($row['NgayGio'])) . " tháng " . date("m, Y", strtotime($row['NgayGio'])) . '</p>';
            //  echo '<form action="php/datve.php?id="'.$row['MaSuKien'] .'method="POST">';
            //echo "<div>"
                echo '<form action="php/datve.php" method="GET">';

                echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['MaSuKien']) . '">';
                echo '<button type="submit" class="add-ticket" >Đặt vé</button>';
                echo '</form>';
                //echo '</div>';
                echo '</div></div>';
            }
        } else {
            echo '<p>Không tìm thấy sự kiện nào phù hợp.</p>';
        }
    }
}
?>
