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
    if (isset($_POST["ChangePassword"])) {
      isset($_POST['usernamechp']) ? $usernamech = $_POST['usernamechp'] : $usernamech  = "";
      isset($_POST['passmember']) ? $passmember = $_POST['passmember'] : $passmember = "";

      $usernamech = $conn->real_escape_string($usernamech);
      $passmember = $conn->real_escape_string($passmember);
      $passwordportal = md5($passmember);
        $sql1 = "UPDATE radcheck SET  value = '$passmember' WHERE username = '$usernamech' ";
        $res1 = $conn->query($sql1);
        $sql2 = "UPDATE userinfo SET  passwordportal = '$passwordportal' WHERE username = '$usernamech' ";
        $res2 = $conn->query($sql2);

        $message = "แก้ไขข้อมูลสมาชิกสำเร็จ";
        $colorfont = "#1cc88a";
    }
    if (isset($_POST["ChangeGroup"])) {
      isset($_POST['usernamech']) ? $usernamech = $_POST['usernamech'] : $usernamech = "";
      isset($_POST['selectgroup']) ? $selectgroup = $_POST['selectgroup'] : $selectgroup = "";

      $usernamech = $conn->real_escape_string($usernamech);
      $selectgroup = $conn->real_escape_string($selectgroup);
        $sql1 = "UPDATE radusergroup SET groupname = '$selectgroup' WHERE username = '$usernamech' ";
        $res1 = $conn->query($sql1);

        $message = "แก้ไขข้อมูลสมาชิกสำเร็จ";
        $colorfont = "#1cc88a";
    }
    if (isset($_POST["DeleteUser"])) {
      isset($_POST['id']) ? $username = $_POST['id'] : $username = "";
        $sql1 = "delete from radcheck  WHERE username  = '$username' ";
        $res1 = $conn->query($sql1);
        $sql2 = "delete from radusergroup  WHERE username  = '$username' ";
        $res2 = $conn->query($sql2);
        $sql3 = "delete from userinfo  WHERE username  = '$username' ";
        $res3 = $conn->query($sql3);
        
        $message = "ลบรายการสำเร็จ";
        $colorfont = "#e74a3b";
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
active
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
            <h1 class="h3 mb-0 text-gray-800">สมาชิกที่ยังไม่ได้จัดกลุ่ม</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./"><i class="fas fa-home text-primary"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">สมาชิกที่ยังไม่ได้จัดกลุ่ม</li>
            </ol>
          </div>
            <div class="card shadow mb-4">
              <div class="card-header">
                <i class="fas fa-table"></i>
                สมาชิกที่ยังไม่จัดกลุ่ม
              </div>
              <div class="card-body">
                <div class="form-group">
                  <div class="form-label-group">
                    <center><?php if(isset($message,$colorfont)) { echo "<h2><font color = '".$colorfont."' > $message </font></h2>";} ?></center>
                  </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><center>ลำดับ</center></th>
                                <th><center>ชื่อผู้ใช้</center></th>
                                <th><center>ชื่อ-สกุล</center></th>
                                <th><center>อีเมลล์</center></th>
                                <th><center>เลขบัตรประจำตัวประชาชน</center></th>
                                <th><center>กลุ่ม</center></th>
                                <th><center>แก้ไข</center></th>
                            </tr>
                        </thead>
                    </table>
                </div>
              </div>
            </div>

    <!-- Modal -->
  <div class="modal fade" id="ChangePassUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">เปลี่ยนรหัสผ่าน</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        
        <form action="" method='POST'>
          <input type="password" id="passmember" name="passmember" class="form-control" placeholder="รหัสผ่านใหม่" required="required">
          <span id="errPassmember"></span>
          <input type="hidden" name="usernamechp" id="usernamechp">
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">ยกเลิก</button>
          <input type="submit" name="ChangePassword" value="ยืนยัน" class="btn btn-primary">
        </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="ChangeGroupUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">เปลี่ยนกลุ่ม</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        
        <form action="" method='POST'>
          <div class="form-group">
                <select class="select2-single-placeholder form-control" id="selectgroup" name="selectgroup">
                  <option value="">เลือกกลุ่ม</option>
                  <?php   
                  $sql2 ='SELECT radgroupreply.groupname FROM radgroupreply group by radgroupreply.groupname UNION SELECT radgroupcheck.groupname FROM radgroupcheck group by radgroupcheck.groupname';
                  $result2 = $conn->query($sql2);
                  if ($result2->num_rows > 0) {
                    // output data of each row
                    while($row2 = $result2->fetch_assoc()) {
                    echo "<option value='" . $row2["groupname"]. "'>" . $row2["groupname"]. "</option>";
                    }
                  } else {
                    echo "0 results";
                  }
                  ?>
                </select>
                <input type="hidden" name='usernamech' id='usernamech' />
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">ยกเลิก</button>
          <input type="submit" name="ChangeGroup" value="ยืนยัน" class="btn btn-primary">
        </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="DeleteUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">ลบผู้ใช้?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        <form action="" method='POST'>
        ต้องการลบผู้ใช้
        <input type="hidden" name="id" id="id">
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">ยกเลิก</button>
          <input type="submit" name="DeleteUser" value="ลบ" class="btn btn-danger">
        </div>
        </form>
      </div>
    </div>
  </div>

<?php include 'layout/mainpage-modal.php' ?>
<script>
      $('#selectgroup').select2({
        placeholder: "กรุณาเลือกกลุ่มผู้ใช้งาน",
        allowClear: true
      });      
    function ChangeGroupUserModal(username){
      $('#usernamech').val(username);
    }
    function ChangePassUserModal(username){
      $('#usernamechp').val(username);
    }
    function deleteuser(id){
      $('#id').val(id);
    }
    $(document).ready(function() {
        $('#dataTable').dataTable({
            "processing": true,
            "ajax": "getuserregistergroup.php",
            "columns": [
                {data: 'num'},
                {data: 'username'},
                {data: 'fullname'},
                {data: 'email'},
                {data: 'cid'},
                {data: 'groupname'},
                {data: 'username' , render : function ( data, type, row, meta )
                  {
                  return type === 'display'  ?
                  '<center><a class="btn btn-primary" href="#" data-toggle="modal" onclick=ChangePassUserModal("'+data+'") data-target="#ChangePassUserModal" title="เปลี่ยนรหัสผ่าน"><i class="fas fa-key"></i></a><a class="btn btn-success" href="#" data-toggle="modal" onclick=ChangeGroupUserModal("'+data+'") data-target="#ChangeGroupUserModal" title="เปลี่ยนกลุ่ม"><i class="fa fa-users"></i></a><a class="btn btn-danger" href="#" data-toggle="modal" onclick=deleteuser("'+data+'") data-target="#DeleteUserModal" title="ลบผู้ใช้"><i class="fa fa-trash"></i></a></center>':"";
                  }
                },
            ],
            'columnDefs': [
              {
                  "targets": 0, // your case first column
                  "className": "text-center",
                  "width": "5%"
              },
              {
                  "targets": 1, // your case first column
                  "className": "text-center",
                  "width": "5%"
              },
              {
                  "targets": 2, // your case first column
                  "className": "text-center",
                  "width": "23%"
              },
              {
                  "targets": 3, // your case first column
                  "className": "text-center",
                  "width": "20%"
              },
              {
                  "targets": 4, // your case first column
                  "className": "text-center",
                  "width": "13%"
              },
              {
                  "targets": 5, // your case first column
                  "className": "text-center",
                  "width": "15%"
              },
              {
                "targets": 6, // your case first column
                  "className": "text-center",
                  "width": "20%"
              }
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
