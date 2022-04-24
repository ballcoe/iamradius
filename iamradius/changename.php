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
      isset($_POST['sys_id']) ? $sys_id = $_POST['sys_id'] : $sys_id = "";
      isset($_POST['sysname']) ? $sysname = $_POST['sysname'] : $sysname = "";

      $sys_id = $conn->real_escape_string($sys_id);
      $sysname = $conn->real_escape_string($sysname);
    
        $sql1 = "UPDATE systemname SET sys_id = '$sys_id' , name = '$sysname'";
        $res1 = $conn->query($sql1);
        
        $message = "เปลี่ยนข้อมูลหน่วยงานเรียบร้อย";
        $colorfont = "#1cc88a";
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
active
<?php include 'layout/mainpage-menu-18.php' ?> <!-- Maintanance Show -->
show
<?php include 'layout/mainpage-menu-19.php' ?> <!-- Change Name Orga -->
active
<?php include 'layout/mainpage-menu-20.php' ?> <!-- Server Status -->
<?php include 'layout/mainpage-menu-21.php' ?> <!-- PHPMYADMIN -->
<?php include 'layout/mainpage-menu-22.php' ?> <!-- Menu End-->
<?php include 'layout/mainpage-navbar.php' ?> 


        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">เปลี่ยนชื่อหน่วยงาน</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./"><i class="fas fa-home text-primary"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">เปลี่ยนชื่อหน่วยงาน</li>
            </ol>
          </div>
            <div class="card shadow mb-4">
              <div class="card-header">
                <i class="fas fa-table"></i>
                เปลี่ยนชื่อหน่วยงาน
              </div>
                <div class="card-body">
                  <div class="form-group">
                    <div class="form-label-group">
                      <center><?php if(isset($message,$colorfont)) { echo "<h2><font color = '".$colorfont."' > $message </font></h2>";} ?></center>
                    </div>
                  </div>
                  <form action = "" method = "POST">
                    <div class="form-group">
                      <div class="form-label-group">
                        <input type="text" id="sys_id" name="sys_id" class="form-control" placeholder="รหัสหน่วยงาน" required="required" autofocus="autofocus">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="form-label-group">
                        <input type="text" id="sysname" name="sysname" class="form-control" placeholder="ชื่อหน่วยงาน" required="required">
                      </div>
                    </div>
                  <button class="btn btn-primary btn-block">แก้ไขข้อมูลหน่วยงาน</button>
                  </form>
                </div>
              </div>

<?php include 'layout/mainpage-modal.php' ?>
<?php include 'layout/mainpage-footer.php' ?>
<?php include 'layout/mainpage-end.php' ?>
