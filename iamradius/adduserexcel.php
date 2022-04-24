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
?>
<?php include 'layout/mainpage-header.php' ?>
<?php include 'layout/mainpage-body.php' ?>
<?php include 'layout/mainpage-menu-01.php' ?> <!-- Dashbroad -->
<?php include 'layout/mainpage-menu-02.php' ?> <!-- User Main -->
active
<?php include 'layout/mainpage-menu-03.php' ?> <!-- User Menu Show -->
show
<?php include 'layout/mainpage-menu-04.php' ?> <!-- View User -->
<?php include 'layout/mainpage-menu-05.php' ?> <!-- View User Register -->
<?php include 'layout/mainpage-menu-06.php' ?> <!-- Add User -->
<?php include 'layout/mainpage-menu-07.php' ?> <!-- Add Excel --> 
active
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
            <h1 class="h3 mb-0 text-gray-800">เพิ่มสมาชิกจากไฟล์ Excel</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./"><i class="fas fa-home text-primary"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">เพิ่มสมาชิกจากไฟล์ Excel</li>
            </ol>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header">
              <i class="fas fa-table"></i>
              เพิ่มสมาชิกจากไฟล์ Excel
            </div>
            <div class="card-body">
            <!--<h1>กำลังพัฒนา</h1>-->
            <?php
            require_once __DIR__.'/SimpleXLSX.php';

            if (isset($_FILES['file'])) {
              
              if ( $xlsx = SimpleXLSX::parse( $_FILES['file']['tmp_name'] ) ) {

              
                $dim = $xlsx->dimension();
                $cols = $dim[0];

                $i =0;
                foreach($xlsx->rows() as $row){
                    if($i != 0){
                      

                        $username =$row[0];
                        $password = $row[1];
                        $firstname = $row[2];
                        $lastname = $row[3];
                        $email = $row[4];
                        $CID = $row[5];
                        $group = $row[6];
                        $datenow = date('Y-m-d H:i:s');
                        $passwordportal = md5($passmember);
                    
                $sql = "SELECT * FROM radcheck WHERE Username = '".$username."'";
                $res = $conn->query($sql);
                $row = mysqli_fetch_row($res);

                  if ($row[0] == NULL) {
                  $sql1 = "SELECT * FROM userinfo WHERE cid = '".$CID."'";
                  $res1 = $conn->query($sql1);
                  $row1 = mysqli_fetch_row($res1);
                    if ($row1[0] == NULL) {
                      $sql2 = "SELECT * FROM userinfo WHERE email = '".$email."'";
                      $res2 = $conn->query($sql2);
                      $row2 = mysqli_fetch_row($res2);
                        if ($row2[0] == NULL) {
                          $sql11 = "INSERT INTO radcheck (id, Username, Attribute, op, Value) VALUES (0, '$username', 'Cleartext-Password', ':=', '$password')";
                          $res11 = $conn->query($sql11);
                          /* adding user information to the userinfo table */
                          $sql12 = "INSERT INTO userinfo (username, firstname, lastname, email, cid, date_regis , passwordportal) VALUES ('$username', '$firstname', '$lastname', '$email', '$CID' ,'$datenow' , '$passwordportal')";
                          $res12 = $conn->query($sql12);
                          /* adding the user to the default group defined */
                          $sql13 = "INSERT INTO radusergroup (UserName, GroupName, priority) VALUES ('$username', '".$group."', '0')";
                          $res13 = $conn->query($sql13);

                          $message = "เพิ่มสมาชิกสำเร็จ";
                          $colorfont = "#1cc88a";
                        } else {
                          $message = "อีเมลล์ถูกผู้งานอื่นใช้งานแล้ว กรุณาเปลี่ยนอีเมลล์ใหม่";
                        $colorfont = "#e74a3b";
                        }                                          
                    } else {
                        $message = "เลขบัตรประจำตัวประชาชนเคยสมาชิกแล้ว";
                        $colorfont = "#e74a3b";
                    }
                  } else {
                      $message = "ชื่อผู้ใช้ (".$username.") เคยเป็นสมาชิกแล้ว กรุณากรอกชื่อผู้ใช้ใหม่";
                      $colorfont = "#e74a3b";
                  }
                            
                    }
                    $i++;
                }
              } else {
                echo SimpleXLSX::parseError();
              }
            }
            ?>
            <div class="form-group">
              <div class="form-label-group">
                <center><?php if(isset($message,$colorfont)) { echo "<h2><font color = '".$colorfont."' > $message </font></h2>";} ?></center>
              </div>
            </div>
            <form  method="post" name="frmExcelImport"
              id="frmExcelImport" enctype="multipart/form-data">
              <div>
                <center>
                  <label><h5>เลือกไฟล์ Excel ที่ต้องการอัพโหลด</h5></label>
                
                  <br><br>
                  <div class="form-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" data-theme="asphalt" name="file" id="file" accept=".xls,.xlsx">
                      <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                    </div>
                  </div>
                      <br>
                  <button type="submit" id="submit" name="import" class="btn btn-success">เพิ่มไฟล์</button>
                  <br><br>
                
                  <label>ดาวน์โหลดตัวอย่างไฟล์ Excel ได้ <a href = "example.xlsx">ที่นี่</a></label>
                </center>
              </div>
            </form>
          </div>
        </div>
<?php include 'layout/mainpage-modal.php' ?>
<script type="text/javascript">
$(function(){
 
    $("#file").on("change",function(){
        var _fileName = $(this).val();
        $(this).next("label").text(_fileName);
    });
 
});
</script>
<?php include 'layout/mainpage-footer.php' ?>
<?php include 'layout/mainpage-end.php' ?>
