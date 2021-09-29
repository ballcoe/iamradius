<?php
session_start();
include("config.php");
 $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
    }
    if (!$_SESSION["UserID"]){ 	
      $sql='select id, username, time from operator where address="'.$_SERVER["REMOTE_ADDR"].'"';		
      $stmt = $conn->prepare($sql);	
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($id, $name, $time);
      if ($stmt->num_rows > 0) {
        while ($stmt->fetch()){
          if (($time_now - $timestamp) < $admin_time){  
            $_SESSION["UserID"] = $row["id"];
            $_SESSION["User"] = $row["username"];
            Header("Location: index");
          } else {
            Header("Location: login");
          }
        }
      }
      Header("Location: login");
      $stmt->free_result();
      $stmt->close;
      $conn->close;
      exit;
    }	
    if (isset($_POST["Addgroup"])) {
    isset($_POST['groupname']) ? $groupname = $_POST['groupname'] : $groupname = "";
    isset($_POST['upload']) ? $upload = $_POST['upload'] : $upload = "";
    isset($_POST['download']) ? $download = $_POST['download'] : $download = "";
    isset($_POST['UserGroupLogin']) ? $UserGroupLogin = $_POST['UserGroupLogin'] : $UserGroupLogin = "";
    isset($_POST['UserGroupColor']) ? $UserGroupColor = $_POST['UserGroupColor'] : $UserGroupColor = "";

    $groupname = $conn->real_escape_string($groupname);
    $upload = $conn->real_escape_string($upload);
    $upload = ($upload * 1024);
    $download = $conn->real_escape_string($download);
    $download = ($download * 1024);
    $UserGroupLogin = $conn->real_escape_string($UserGroupLogin);
    $UserGroupColor = $conn->real_escape_string($UserGroupColor);

    $sql11 = "SELECT groupname , color FROM groupcolor WHERE groupname = '".$groupname."' or color = '".$UserGroupColor."' ";
    $res = $conn->query($sql11);
    $row = mysqli_fetch_row($res);
    if ($row[0] == NULL) {
        $sql1 = "INSERT INTO radgroupreply (groupname,attribute,op,value) VALUES ('$groupname', 'WISPr-Bandwidth-Max-Up', ':=', '$upload ')";
        $sql2 = "INSERT INTO radgroupreply (groupname,attribute,op,value) VALUES ('$groupname', 'WISPr-Bandwidth-Max-Down', ':=', '$download')";
        $sql3 = "INSERT INTO radgroupreply (groupname,attribute,op,value) VALUES ('$groupname', 'Port-Limit', ':=', '$UserGroupLogin')";
        $sql4 = "INSERT INTO radgroupreply (groupname,attribute,op,value) VALUES ('$groupname', 'Idle-Timeout', ':=', '3600')";
        $sql5 = "INSERT INTO groupcolor (groupname,color) VALUES ('$groupname', '$UserGroupColor')";
        $res1 = $conn->query($sql1);
        $res2 = $conn->query($sql2);
        $res3 = $conn->query($sql3);
        $res4 = $conn->query($sql4);
        $res5 = $conn->query($sql5);

        $message = "เพิ่มกลุ่มสำเร็จ";
        $colorfont = "#1cc88a";
    } elseif ($row[0] == $groupname and $row[1] != $UserGroupColor) {
      $message = "ชื่อกลุ่มนี้เคยสร้างขึ้นแล้ว กรุณากรอกชื่อกลุ่มใหม่";
      $colorfont = "#e74a3b";
    } else {
      $message = "สีประจำกลุ่มถูกเลือกใช้แล้ว กรุณาเลือกสีใหม่";
      $colorfont = "#e74a3b";
    }
  }
?>
<?php include 'layout/mainpage-header.php' ?>
<?php include 'layout/mainpage-body.php' ?>
<?php include 'layout/mainpage-menu-01.php' ?> <!-- Dashbroad -->
<?php include 'layout/mainpage-menu-02.php' ?> <!-- User Main -->
<?php include 'layout/mainpage-menu-03.php' ?> <!-- User Menu Show -->
<?php include 'layout/mainpage-menu-04.php' ?> <!-- View User -->
<?php include 'layout/mainpage-menu-05.php' ?> <!-- View User Register -->
<?php include 'layout/mainpage-menu-06.php' ?> <!-- Add User -->
<?php include 'layout/mainpage-menu-07.php' ?> <!-- Add Excel --> 
<?php include 'layout/mainpage-menu-08.php' ?> <!-- Group Main -->
acive
<?php include 'layout/mainpage-menu-09.php' ?> <!-- Group Menu Show -->
show
<?php include 'layout/mainpage-menu-10.php' ?> <!-- All Group -->
<?php include 'layout/mainpage-menu-11.php' ?> <!-- Add Group -->
active
<?php include 'layout/mainpage-menu-12.php' ?> <!-- Report Main -->
<?php include 'layout/mainpage-menu-13.php' ?> <!-- Report Show -->
<?php include 'layout/mainpage-menu-14.php' ?> <!-- View User Online -->
<?php include 'layout/mainpage-menu-15.php' ?> <!-- Top 10 User -->
<?php include 'layout/mainpage-menu-16.php' ?> <!-- Maintanance Main -->
<?php include 'layout/mainpage-menu-17.php' ?> <!-- Maintanance Show -->
<?php include 'layout/mainpage-menu-18.php' ?> <!-- Change Name Orga -->
<?php include 'layout/mainpage-menu-19.php' ?> <!-- Server Status -->
<?php include 'layout/mainpage-menu-20.php' ?> <!-- PHPMYADMIN -->
<?php include 'layout/mainpage-menu-21.php' ?> <!-- Menu End-->
<?php include 'layout/mainpage-navbar.php' ?> 


        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">เพิ่มกลุ่ม</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./"><i class="fas fa-home text-primary"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">เพิ่มกลุ่ม</li>
            </ol>
          </div>
          <div class="card card-register mx-auto mt-5">
            <div class="card-body">
              <form action = "" method = "POST">
                <div class="form-group">
                  <div class="form-label-group">
                    <center><?php if(isset($message,$colorfont)) { echo "<h2><font color = '".$colorfont."' > $message </font></h2>";} ?></center>
                  </div>
                </div>
                <div class="form-group">
                  <div class="form-label-group">
                    <input type="text" id="groupname" name="groupname" class="form-control"  placeholder="ชื่อกลุ่ม (A-Z,a-z,0-9 เท่านั้น)" required="required" autofocus="autofocus">
                    <span id="errGroupname"></span>
                  </div>
                </div>
                <div class="form-group">
                  <div class="form-row">
                    <div class="col-md-6">
                      <div class="form-label-group">
                        <input type="text" id="upload" name="upload" class="form-control" onkeypress="return isNumber(event);" placeholder="อัพโหลด (kbps)" required="required">
                        <span id="errUpload"></span>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-label-group">
                        <input type="text" id="download" name="download" onkeypress="return isNumber(event);" class="form-control" placeholder="ดาวน์โหลด (kbps)" required="required">
                        <span id="errDownload"></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="form-label-group">
                    <input id="UserGroupLogin" name="UserGroupLogin" class="form-control" onkeypress="return isNumber(event);" placeholder="จำนวนครั้งต่อสมาชิกที่สามารถเข้าสู่ระบบ" required="required">
                    <span id="errUserGroupLogin"></span>
                  </div>
                </div>
                <div class="form-group">
                  <div class="form-row">
                    <div class="col-md-2">
                      <div class="form-label-group">
                        กรุณาเลือกสีประจำกลุ่ม :
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-label-group">
                        <input id="UserGroupColor" name="UserGroupColor" class="form-control" type="color" value="#000000">
                      </div>
                    </div>
                  </div>
                </div>
                <center><input type="submit" name="Addgroup" value="เพิ่มกลุ่ม" class="btn btn-primary btn-block-30"></center>
              </form>
            </div>
          </div>

<?php include 'layout/mainpage-modal.php' ?>
<?php include 'layout/mainpage-footer.php' ?>
<?php include 'layout/mainpage-end.php' ?>
