<?php
include "../headeruser.php";
    // include "include/connect.php";
    // session_start();
    // include 'connect.php';

    //  Kiểm tra đăng nhập
    if (!isset($_SESSION['MaNguoiDung'])) {
        echo "<script>
            alert('⚠️ Bạn cần đăng nhập để thêm vé!');
            window.location.href='../php/dangnhap.php';
        </script>";
        exit;
    }
    $id= $_GET['id']; //lấy id sự kiện
    $sql_day = "SELECT DISTINCT DayGhe FROM Ghe WHERE MaSuKien='$id'";
    $result_day = mysqli_query($conn, $sql_day);
    //  Cài múi giờ Việt Nam để tránh sai lệch
   date_default_timezone_set('Asia/Ho_Chi_Minh');
    $row_sk = mysqli_fetch_array(mysqli_query($conn,"select * from SuKien where MaSuKien='$id'"));
    $now = date("Y-m-d H:i:s");
    $bd=  date("Y-m-d H:i:s",strtotime($row_sk['NgayBatDau']));
    $kt=  date("Y-m-d H:i:s",strtotime($row_sk['NgayKetThuc']));
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            padding: 0;
            margin: 0;
        }
        .khung_chitiet{
            width:100%;
            height:400px;
            background:linear-gradient(145deg, #434343, #000000); /* gradient đen xám */;
            display:flex;
            justify-content:center;
            align-items:center;
        }
        .khung_chitiet .chitiet {
            width:80%;
            height:300px;
            display:flex;
            justify-content: center;

            
        }

        .noidung.sk{
            background-color: #2e3137;
            width:40%;
            border:none;
            border-radius:30px;
            border-right: 5px dashed #191a1dff;
            
        }
        .ttsk.content{
            margin:30px;
            line-height:1.5;
            margin-bottom:0;
        }

        .noidung.img{
            /* background-color: #b2ba1fff; */
            width: 60%;
            border:none;
            border-radius:30px;
            /* border-left: 2px dashed black; */
            
        }
        img{
            width: 100%;
            border-radius:30px;
            height: 100%;
            order-left: 4px dashed black;
        }
        h3{
            color:white;
            font-weight:bold;

        }
        span{
            padding: 12px;
            /* transform: translateY(-10px); */
            color: #2dc275;
            font-weight:bold;
        }
        svg{
            padding-top:17px;
        }
        /* dãy ghế */
        .dayghe {
  margin-bottom: 15px;
}

.danhsach_ghe {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.ghe_sk {
  width: 40px;
  height: 40px;
  border: 1px solid #333;
  border-radius: 6px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 5px;
  cursor: pointer;
  transition: 0.2s;
}

/* Ghế trống */
.ghe_sk.trong {
  background-color: white;
  border : 2px solid #2dc275;
}

/* Ghế đã đặt */
.ghe_sk.dat {
  background-color: #f44336;
  color: white;
  cursor: not-allowed;
  opacity: 0.7;
  border : 2px solid #ef1e0fff;
}

/* Ghế đang được chọn */
.ghe_chon {
  background-color: #2dc275 !important;
  color: white;
}
/*  */
.form_themvaovecuatoi , .gioithieu {
    margin: 30px;
    background-color: white !important;
}
body{
    background-color: white !important;
}
    </style>
</head>
<body>
    <div class="khung_chitiet" >
        <div class="chitiet">
            <div class="noidung sk">
                <div style="height:calc(0.7*300px);" class="ttsk content">
                    <!-- Tên Sự kiện -->
                    <h3><?php echo $row_sk['TenSuKien'];?></h3>
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                    </svg> -->
                    <!-- Thời gian -->
                    <img style="width:18px;height:18px; border-radius:0px;margin-top:5px;"src="../img/date.png" alt="">
                    <span>
                        <?php
                    $ngaygio = $row_sk['NgayGio']; 
                    $timestamp = strtotime($ngaygio);
                    echo date('j', $timestamp) . " tháng " . date('n, Y', $timestamp) . " – Từ " . date('H:i', $timestamp);
                        ?>
                    </span> <br>
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                    <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/>
                    <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                    </svg> -->
                    <!-- Địa điểm -->
                     <img style="width:18px;height:18px; border-radius:0px;margin-top:5px;"src="../img/checkin.png" alt="">
                    <span><?php echo $row_sk['DiaDiem'];?></span>
                </div>
                <div style="height:100px;" class="ttsk btn">
                    <hr style=" margin-bottom: 15px;" >
                    <span style="color:white;font-size:20px; margin: 10px ;">Giá từ : </span>
                    <span style="color:#2dc275;font-size:25px;">
                        <?php $min=mysqli_fetch_array(mysqli_query($conn,"select min(gia) as MIN from Ghe where masukien='$id'")); 
                        echo number_format($min['MIN']).'đ' ?>
                    </span>
                </div>
                
            </div>

            <div class="noidung img">
                <img src="<?php echo '../img/'.$row_sk['HinhAnh'];?>" alt="hình ảnh">
            </div>
        </div>
    </div>

    <!-- Giới thiệu -->
     <div class="gioithieu">
        <h1 style="color:gray;">Giới thiệu</h1>
        <hr style="margin-top:20px;">
        <div class="mota" style="margin:20px 0px;">
            <!-- Đây là nội dung mô tả của sự kiện !!! -->
             <?php echo $row_sk['MoTa'];?>
        </div>
        <hr>
     </div>
    <!-- Kiểm tra thời gian -->
     <?php 
        if ( (strtotime($now) >= strtotime($bd)) && (strtotime($now) <= strtotime($kt)) )
        {
            include "../php/form_datve.php";
        }
        else if ( (strtotime($now) < strtotime($bd)) && (strtotime($now) < strtotime($kt)) ) {
            echo "<span style ='margin:30px; padding:20px; font-size: 20px; font-weight:bold; border:none; background: #2dc275; color: #f4f4f0ff; border-radius:10px;'>Sự kiện chưa mở bán vé</span>";
        }
        else{
            echo "<span style ='margin:30px; padding:20px; font-size: 20px; font-weight:bold; border:none; background: gray; color: white; border-radius:10px;'>Sự kiện đã kết thúc</span>";
        }
     ?>

     
     <!-- <span style ='margin:20px; padding:20px; font-size: 20px; font-weight:bold; border:none; background: gray; color: white; border-radius:10px;'>Sự kiện đã kết thúc</span> -->
        <!-- <span style ='margin:20px; padding:20px; font-size: 20px; font-weight:bold; border:none; background: #2dc275; color: #f9f7f7ff; border-radius:10px;'>Sự kiện chưa mở bán vé</span> -->
</body>
</html>

<?php 
        