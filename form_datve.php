<!-- Form thanh toán -->
      <div class="form_themvaovecuatoi">
    <form action="../php/thanhtoan.php?user=<?php echo $_SESSION['MaNguoiDung'].'&sk='.$row_sk['MaSuKien'];?>" 
        method="POST" id="formDatVe">
  <h1 style="color:gray;">Danh sách ghế ngồi</h1>

  <?php while ($row_day = mysqli_fetch_assoc($result_day)) { 
      $day = $row_day['DayGhe'];
      $sql_ghe = "SELECT SoGhe, TrangThai, Gia 
            FROM Ghe 
            WHERE MaSuKien='$id' AND DayGhe='$day' 
            ORDER BY CAST(SoGhe AS UNSIGNED) ASC";
      $result_ghe = mysqli_query($conn, $sql_ghe);
  ?>
      <div class="dayghe">
          <h4 style="font-weight:bold; margin:10px 10px;font-size:20px;">Dãy <?php echo $day; ?></h4>
          <div class="danhsach_ghe">
              <?php while ($row_ghe = mysqli_fetch_assoc($result_ghe)) { ?>
                  <div class="ghe_sk <?php echo " ".$row_ghe['TrangThai'] ?>"  
                       data-gia="<?php echo $row_ghe['Gia']; ?>"> 
                      <?php echo $day . $row_ghe['SoGhe']; ?>
                  </div>
              <?php } ?>
          </div>
      </div>
  <?php } ?>

  <!-- Ghế chọn -->
  <div style="display:flex;margin-top:20px;" class="list_ghe_chon">
      <div style="font-weight:bold; font-size: 20px; color: #2dc275;">Ghế đã chọn: </div>
      <div style="font-weight:bold; font-size: 20px; color: black; margin-left:10px;" id="ghedachon">    
      </div>  
  </div>

  <div style="display:flex; margin-top:20px;" class="tongtien">
      <div style="font-weight:bold; font-size: 20px; color: #2dc275;">Tổng tiền: </div>
      <div style="font-weight:bold; font-size: 20px; color: black; margin-left:10px;" id="tongtien">0</div> 
      <b>đ</b>
  </div>

  <!-- Nút gửi -->
  <div style="margin-top:20px;">
      <button type="submit" 
              style="padding: 12px 25px; background-color: #2dc275; border: none; border-radius: 10px;
                     color: white; font-weight: bold; cursor: pointer; font-size: 18px;">
          Thanh toán
      </button>
  </div>
  <!-- Phần ẩn để lấy dữ liệu -->
    <input type="hidden" name="id_sukien" value="<?php echo $id; ?>">
    <input type="hidden" name="tongtien" id="input_tongtien">
    <input type="hidden" name="ghe_chon" id="input_ghe_chon">
</form>
      </div>


     
        

    <script>
    var dachon = document.getElementById('ghedachon');
    var tongtien = document.getElementById('tongtien');
    var tinh =0;
    document.addEventListener("DOMContentLoaded", function() {
    // Lấy tất cả phần tử có class "ghe_sk"
    var gheList = document.getElementsByClassName("ghe_sk");

    // Duyệt qua từng ghế
    for (var i = 0; i < gheList.length; i++) {
        gheList[i].onclick = function() {
        // Nếu ghế đã đặt => không cho chọn
        if (this.classList.contains("dat")) {
            alert("Ghế này đã được đặt!");
            return;
        }
        var gia = parseInt(this.getAttribute("data-gia"));
        // Nếu ghế đang chọn => bỏ chọn, ngược lại chọn
        if (this.classList.contains("ghe_chon")) {
            this.classList.remove("ghe_chon");
                tinh-=gia;
            
        } else {
        this.classList.add("ghe_chon");
        tinh+=gia;
        
        }
        capNhatDanhSach();
        tongtien.innerText=tinh;
    };
  }
});
function capNhatDanhSach() {
    var gheChon = document.getElementsByClassName("ghe_chon");
    var ds = [];

    for (var j = 0; j < gheChon.length; j++) {
        //lấy ghế
      var ghe = gheChon[j].innerText;
       
      ds.push(ghe);
    }

    // Hiển thị ra HTML
    if (ds.length > 0) {
      dachon.innerText = ds.join(", ");
    } else {
      dachon.innerText = "";
    }
    
  }

  document.getElementById("formDatVe").onsubmit = function(e) {
    var gheChon = document.getElementsByClassName("ghe_chon");
    var ds = [];
    for (var j = 0; j < gheChon.length; j++) {
        ds.push(gheChon[j].innerText.trim());
    }

    if (ds.length === 0) {
        alert("Vui lòng chọn ít nhất 1 ghế!");
        e.preventDefault();
        return false;
    }

    document.getElementById("input_ghe_chon").value = JSON.stringify(ds);
    document.getElementById("input_tongtien").value = tinh;
    };


</script>