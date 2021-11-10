<?php
include("iamradius/config.php");

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
mysqli_set_charset($conn, DB_CHARSET);
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/undraw_profile.svg" rel="icon">
  <title>ระบบสารสนเทศ <?php
    $sqlsystemname = "select * from systemname";
    $resultsystemname = $conn->query($sqlsystemname);
    if ($resultsystemname->num_rows > 0) {
      while($row = $resultsystemname->fetch_assoc()) {
        echo $row['name'];
      }
    } ?></title>
  <link href="./vendor/fontawesome-free/css/all.css" rel="stylesheet" type="text/css">
  <link href="./vendor/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="./css/ruang-admin.css" rel="stylesheet">

</head>

<body class="bg-gradient-login">
  <!-- Register Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <div class="text-center">
                    <h1 class="h3 mb-0 text-gray-800">ยินดีต้อนรับสู่งานสารสนเทศ <?php
                    $sqlsystemname = "select * from systemname";
                    $resultsystemname = $conn->query($sqlsystemname);
                    if ($resultsystemname->num_rows > 0) {
                      while($row = $resultsystemname->fetch_assoc()) {
                        echo $row['name'];
                      }
                    } ?></h1><br>
                    <hr>
                    <h1 class="h2 text-gray-900 mb-4"><a href='https://www.google.co.th' target = '_blank'><font color='blue'>G</font><font color='red'>o</font><font color='orange'>o</font><font color='blue'>g</font><font color='green'>l</font><font color='red'>e</font></a></h1>
                    <hr>
                    <h1 class="h6 text-gray-900 mb-4"> Your IP Address is <?php echo $_SERVER['REMOTE_ADDR']; ?> </h1>
                    <h1 class="h6 text-gray-900 mb-4"> Server IP Address is <?php echo $_SERVER['SERVER_ADDR']; ?> </h1>
                    <hr>
                  </div>
                  <br>
                  <h1 class="h6 text-gray-900 mb-4">- เปิดใช้ระบบ Authentication 1 มกราคม พ.ศ.2555 ท่านสามารถลงทะเบียนได้ที่ <a href ='register' target='_blank'> ลงทะเบียน </a></h1>
                  <h1 class="h6 text-gray-900 mb-4">- ท่านสามารถแก้ไขข้อมูลสมาชิกของท่านได้ที่ <a href ='users' target='_blank'> แก้ไขข้อมูลสมาชิก </a></h1>
                  <h1 class="h6 text-gray-900 mb-4"><font color ='red'> - มีปัญหาในการใช้งาน ติดต่อ <a href="tel:09-2776-7217">09-2776-7217</a></font></h1>
                  <h1 class="h6 text-gray-900 mb-4"><font color ='red'> - ห้าม Fix IP โดยเด็ดขาด เพราะจะไม่สามารถ Login ได้ และหาก IP ชนกัน จะทำให้ทั้งระบบมีปัญหา </font></h1>
                  <h1 class="h6 text-gray-900 mb-4">- ขอความร่วมมือ ตั้งค่าโปรแกรมดาวน์โหลด เช่น IDM หรือ Flashget ให้ดาวน์โหลดทีละไฟล์ และตั้งค่าการเชื่อมต่อให้เป็น 1 โดยเฉพาะหากท่านเชื่อมต่อด้วยระบบ WiFi </h1>
                  <h1 class="h6 text-gray-900 mb-4">- ขอความร่วมมือ งดการใช้โปรแกรมประเภท P2P เช่น Bittorrent เนื่องจากโปรแกรมเหล่านี้ จะสร้างการเชื่อมต่อปริมาณมาก ทำให้ผู้ใช้ท่านอื่น มีปัญหาในการใช้งาน</h1>
                  <br><hr>
                  <div class="text-center">
                    <h1 class="h6 text-gray-900 mb-4">ศูนย์สารสนเทศ <?php
                    $sqlsystemname = "select * from systemname";
                    $resultsystemname = $conn->query($sqlsystemname);
                    if ($resultsystemname->num_rows > 0) {
                      while($row = $resultsystemname->fetch_assoc()) {
                        echo $row['name'];
                      }
                    } ?></h1>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Register Content -->
  <script src="./vendor/jquery/jquery.min.js"></script>
  <script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="./vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="./js/ruang-admin.min.js"></script>
</body>

</html>