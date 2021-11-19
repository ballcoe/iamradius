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
    if (isset($_POST["ChangeInfo"])) {
    isset($_POST['id']) ? $groupname = $_POST['id'] : $groupname = "";
    isset($_POST['upload']) ? $upload = $_POST['upload'] : $upload = "";
    isset($_POST['download']) ? $download = $_POST['download'] : $download = "";
    isset($_POST['profileuser']) ? $profileuser = $_POST['profileuser'] : $profileuser = "";
    isset($_POST['UserGroupColor']) ? $UserGroupColor = $_POST['UserGroupColor'] : $UserGroupColor = "";

	  $groupname = $conn->real_escape_string($groupname);
    $upload = $conn->real_escape_string($upload);
    $upload = ($upload * 1024);
    $download = $conn->real_escape_string($download);
    $download = ($download * 1024);
    $profileuser =  $conn->real_escape_string($profileuser);
    $UserGroupColor =  $conn->real_escape_string($UserGroupColor);
    //$UserGroupLogin = $conn->real_escape_string($UserGroupLogin);
       $sql1 = "UPDATE  radgroupreply SET value = '$upload' WHERE attribute = 'WISPr-Bandwidth-Max-Up' AND  groupname ='$groupname'";
       $sql2 = "UPDATE  radgroupreply SET value = '$download' WHERE attribute = 'WISPr-Bandwidth-Max-Down' AND groupname = '$groupname'";
       $sql3 = "UPDATE  radgroupreply SET value = '$profileuser' WHERE attribute = 'Port-Limit' AND groupname = '$groupname'";
       $sql4 = "UPDATE  groupcolor SET color = '$UserGroupColor' WHERE groupname = '$groupname'";
        $res1 = $conn->query($sql1);
        $res2 = $conn->query($sql2);
        $res3 = $conn->query($sql3);
        $res4 = $conn->query($sql4);
        
        $message = "แก้ไขข้อมูลกลุ่มสำเร็จ";
        $colorfont = "#1cc88a";
  }
  if (isset($_POST["DeleteGroup"])) {
    isset($_GET['id']) ? $id = $_GET['id'] : $id  = "";

	//$id = $conn->real_escape_string($id); // เพื่อทดสอบ
    isset($_POST['idg']) ? $groupname = $_POST['idg'] : $groupname = "";
    $sqlcountuser = "select count(groupname) from radusergroup where groupname = '$groupname'";
    $rescountuser = $conn->query($sqlcountuser);
    $row = mysqli_fetch_row($rescountuser);
    if ($row[0] == "0") {
        $sql1 = "delete from radgroupreply  WHERE groupname  = '$groupname' ";
        $res1 = $conn->query($sql1);
        $sql2 = "delete from groupcolor  WHERE groupname  = '$groupname' ";
        $res2 = $conn->query($sql2);
        $message = "ลบรายการสำเร็จ";
        $colorfont = "#e74a3b";
    }else{
        $message = "ไม่สามารถลบกลุ่มได้ เนื่องจากมีสมาชิกอยู่ในกลุ่ม";
        $colorfont = "#e74a3";
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
active
<?php include 'layout/mainpage-menu-09.php' ?> <!-- Group Menu Show -->
show
<?php include 'layout/mainpage-menu-10.php' ?> <!-- All Group -->
active
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
            <h1 class="h3 mb-0 text-gray-800">กลุ่มทั้งหมด</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./"><i class="fas fa-home text-primary"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">กลุ่มทั้งหมด</li>
            </ol>
          </div>
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            กลุ่มทั้งหมด
                        </div>
                      <div class="card-body">
                        <div class="form-group">
                            <div class="form-label-group">
                                <center><?php if(isset($message,$colorfont)) { echo "<h2><font color = '".$colorfont."' > $message </font></h2>";} ?></center>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th><center>ลำดับ</center></th>
                                        <th><center>ชื่อกลุ่ม</center></th>
                                        <th><center>อัพโหลด (kbps)</center></th>
                                        <th><center>ดาวน์โหลด (kbps)</center></th>
                                        <th><center>จำนวนเครื่องที่เข้าใช้งานต่อผู้ใช้</center></th>
                                        <th><center>แก้ไข</center></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                      </div>
                    </div>
        <!-- Change Group Modal -->
    <div class="modal fade" id="ChangeGroupInfoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">เปลี่ยนข้อมูลกลุ่ม</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                
                    <form action="" method='POST'>
                    <input type="text" id="upload" name="upload" class="form-control" placeholder="อัพโหลด (kbps)" required="required"><br>
                    <input type="text" id="download" name="download" class="form-control" placeholder="ดาวน์โหลด (kbps)" required="required"><br>
                    <input type="text" id="profileuser" name="profileuser" class="form-control" placeholder="จำนวนครั้งต่อสมาชิก" required="required"><br>
                    กรุณาเลือกสีประจำกลุ่ม : <input id="UserGroupColor" name="UserGroupColor" class="form-control" type="color">
                    <input type="hidden" name="id" id="id">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">ยกเลิก</button>
                    <input type="submit" name="ChangeInfo" value="เปลี่ยนข้อมูลกลุ่ม" class="btn btn-primary">
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="DeleteGroupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ลบกลุ่ม?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method='POST'>
                    ต้องการลบกลุ่ม
                    <input type="hidden" name="idg" id="idg">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">ยกเลิก</button>
                    <input type="submit" name="DeleteGroup" value="ลบ" class="btn btn-danger">
                </div>
                </form>
            </div>
        </div>
    </div>
<?php include 'layout/mainpage-modal.php' ?>
    <script>
        $(document).ready(function() {
        $('#dataTable2').dataTable({
            "processing": true,
            "ajax": "getgroup.php",
            "columns": [
                {data: 'num'},
                {data: 'groupname'},
                {data: 'upload'},
                {data: 'download'},
                {data: 'portlimit'},
                {data: 'groupname' , render : function ( data, type, row, meta )
                  {
                  return type === 'display'  ?
                  '<center><a class="btn btn-primary" onclick=setdata("'+row.groupname+'","'+row.upload+'","'+row.download+'","'+row.portlimit+'","'+row.color+'") href="#"  data-toggle="modal" data-target="#ChangeGroupInfoModal" title="เปลี่ยนข้อมูลกลุ่ม"><i class="fa fa-edit"></i></a><a class="btn btn-danger" href="#" onclick=deletegroup("'+data+'") data-toggle="modal" data-target="#DeleteGroupModal" title="ลบกลุ่ม"><i class="fa fa-trash"></i></a></center>':"";
                  }
                },
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
                  "width": "22%"
              },
              {
                  "targets": 5, // your case first column
                  "className": "text-center",
                  "width": "15%"
              },
            ],
            "language": {
              "lengthMenu": "แสดง _MENU_ แถวต่อหน้า",
              "zeroRecords": "ไม่มีข้อมูล",
              "info": "แสดงหน้าที่ _PAGE_ จาก _PAGES_ หน้า",
              "sSearch": "ค้นหา",
              "infoEmpty": "ไม่พบข้อมูลค้นหา",
              "infoFiltered": "(จากทั้งหมด _MAX_ กลุ่ม)",
              "paginate": {
                "next": "ถัดไป",
                "previous": "ก่อนหน้า"
              }
            }
      });
    });
        function setdata(name,max,min,portse,color){
            $('#upload').val(max);
            $('#download').val(min);
            $('#profileuser').val(portse);
            $('#UserGroupColor').val(color);
            $('#id').val(name);
        }
        function deletegroup(gname){
        //var txt;
        $('#idg').val(gname);
        //var r = confirm("คุณต้องการลบรายการนี้หรือไม่");
        //if (r == true) {
        //window.location.href='deletegroup.php?id='+gname;
        //} 
        }
    </script>
<?php include 'layout/mainpage-footer.php' ?>
<?php include 'layout/mainpage-end.php' ?>
