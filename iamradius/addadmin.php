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
    if (count($_POST) > 0) {
      $username= $_POST['Username'];
      $opername= $_POST['Opername'];
      $result = mysqli_query($conn, "SELECT * from operator where username = '$username'");
      $row = mysqli_fetch_array($result);
      $newpassword = md5($_POST['newPassword']);
      if ($username == $row["username"]) {
        $message = "ชื่อผู้ดูแลระบบซ้ำ";
        $colorfont = "#e74a3b";
      } else {
        mysqli_query($conn, "INSERT INTO operator (username,password,opername) VALUE ('$username','$newpassword','$opername') ");
        $message = "เพิ่มผู้ดูแลระบบเรียบร้อย";
        $colorfont = "#1cc88a";
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
<?php include 'layout/mainpage-menu-09.php' ?> <!-- Group Menu Show -->
<?php include 'layout/mainpage-menu-10.php' ?> <!-- All Group -->
<?php include 'layout/mainpage-menu-11.php' ?> <!-- Add Group -->
<?php include 'layout/mainpage-menu-12.php' ?> <!-- Report Main -->
<?php include 'layout/mainpage-menu-13.php' ?> <!-- Report Show -->
<?php include 'layout/mainpage-menu-14.php' ?> <!-- View User Online -->
<?php include 'layout/mainpage-menu-15.php' ?> <!-- View User Report -->
<?php include 'layout/mainpage-menu-16.php' ?> <!-- Top 10 User -->
<?php include 'layout/mainpage-menu-17.php' ?> <!-- Maintanance Main -->
<?php include 'layout/mainpage-menu-18.php' ?> <!-- Maintanance Show -->
<?php include 'layout/mainpage-menu-19.php' ?> <!-- Change Name Orga -->
<?php include 'layout/mainpage-menu-20.php' ?> <!-- Server Status -->
<?php include 'layout/mainpage-menu-21.php' ?> <!-- PHPMYADMIN -->
<?php include 'layout/mainpage-menu-22.php' ?> <!-- Menu End-->
<?php include 'layout/mainpage-navbar.php' ?> 


        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">เพิ่มผู้ดูแลระบบ</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./"><i class="fas fa-home text-primary"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">เพิ่มผู้ดูแลระบบ</li>
            </ol>
          </div>

          <div class="card shadow mb-4">
            <div class="card-header">
              <i class="fas fa-table"></i>
              เพิ่มผู้ดูแลระบบ
            </div>
            <div class="card-body">
                <form name="frmChange" action="" method="POST" onSubmit="return validatePassword()">
                  <div class="form-group">
                    <div class="form-label-group">
                      <center><?php if(isset($message,$colorfont)) { echo "<h2><font color = '".$colorfont."' > $message </font></h2>";} ?></center>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-label-group">
                      <input type="text" id="Username" name="Username" class="form-control" placeholder="ชื่อผู้ใช้" required="required">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-label-group">
                      <input type="text" id="Opername" name="Opername" class="form-control" placeholder="ชื่อ-สกุล ผู้ดูแลระบบ" required="required">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-label-group">
                      <input type="password" id="pwd" name="newPassword" class="form-control" placeholder="รหัสผ่าน" required="required">
                      <span id="errPwd"></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-label-group">
                      <input type="password" id="cpwd" name="confirmPassword" class="form-control" placeholder="ยืนยันรหัสผ่าน" required="required">
                      <span id="errcPwd"></span>
                    </div>
                  </div>
                  <center><button class="btn btn-primary btn-block50">เพิ่มผู้ดูแลระบบ</button></center>
                </form>
            </div>
          </div>

<?php include 'layout/mainpage-modal.php' ?>
<?php include 'layout/mainpage-footer.php' ?>
<?php include 'layout/mainpage-end.php' ?>
