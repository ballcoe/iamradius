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
active
<?php include 'layout/mainpage-menu-13.php' ?> <!-- Report Show -->
show
<?php include 'layout/mainpage-menu-14.php' ?> <!-- View User Online -->
active
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
            <h1 class="h3 mb-0 text-gray-800">สมาชิกที่ออนไลน์ขณะนี้</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./"><i class="fas fa-home text-primary"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">สมาชิกที่ออนไลน์ขณะนี้</li>
            </ol>
          </div>
            <div class="card shadow mb-4">
              <div class="card-header">
                <i class="fas fa-table"></i>
                รายชื่อผู้ใช้ที่กำลังใช้งาน
              </div>
              <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width = "10%"><center>ลำดับ</center></th>
                                <th width = "19%"><center>ชื่อผู้ใช้</center></th>
                                <th width = "19%"><center>ชื่อ-สกุล</center></th>
                                <th width = "15%"><center>IP-Address</center></th>
                                <th width = "15%"><center>Mac-Address</center></th>
                                <th width = "22%"><center>เวลาเริ่มใช้งาน</center></th>
                            </tr>
                        </thead>
                    </table>
                </div>
              </div>
            </div>

<?php include 'layout/mainpage-modal.php' ?>
<script>
  $(document).ready(function() {
        $('#dataTable').dataTable({
            "processing": true,
            "ajax": "getuseronline.php",
            "columns": [
                {data: 'num'},
                {data: 'username'},
                {data: 'fullname'},
                {data: 'ip'},
                {data: 'mac'},
                {data: 'date'},
            ],
            'columnDefs': [
              {
                  "targets": 0, // your case first column
                  "className": "text-center",
                  "width": "10%"
              },
              {
                  "targets": 1, // your case first column
                  "className": "text-center",
                  "width": "19%"
              },
              {
                  "targets": 2, // your case first column
                  "className": "text-center",
                  "width": "19%"
              },
              {
                  "targets": 3, // your case first column
                  "className": "text-center",
                  "width": "15%"
              },
              {
                  "targets": 4, // your case first column
                  "className": "text-center",
                  "width": "15%"
              },
              {
                  "targets": 5, // your case first column
                  "className": "text-center",
                  "width": "22%"
              },
            ],
            "language": {
              "lengthMenu": "แสดง _MENU_ แถวต่อหน้า",
              "zeroRecords": "ไม่มีข้อมูล",
              "info": "แสดงหน้าที่ _PAGE_ จาก _PAGES_ หน้า",
              "sSearch": "ค้นหา",
              "infoEmpty": "ไม่พบข้อมูลค้นหา",
              "infoFiltered": "(จากทั้งหมด _MAX_ คน)",
              "paginate": {
                "next": "ถัดไป",
                "previous": "ก่อนหน้า"
              }
            }
      });
    });
</script>
<?php include 'layout/mainpage-footer.php' ?>
<?php include 'layout/mainpage-end.php' ?>
