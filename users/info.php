<?php
session_start();
include("../iamradius/config.php");
 $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
    }
    if (!$_SESSION["UserIDPortal"]){ 	
      $sql='select id, username, time from userinfo where address="'.$_SERVER["REMOTE_ADDR"].'"';		
      $stmt = $conn->prepare($sql);	
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($id, $name, $time);
      if ($stmt->num_rows > 0) {
        while ($stmt->fetch()){
          if (($time_now - $timestamp) < $admin_time){  
            $_SESSION["UserIDPortal"] = $row["id"];
            $_SESSION["UserPortal"] = $row["username"];
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
      isset($_POST['id']) ? $username = $_POST['id'] : $username = "";
      isset($_POST['firstname']) ? $firstname = $_POST['firstname'] : $firstname = "";
      isset($_POST['lastname']) ? $lastname = $_POST['lastname'] : $lastname = "";
      isset($_POST['email']) ? $email = $_POST['email'] : $email = "";
  
      $username = $conn->real_escape_string($username);
      $firstname = $conn->real_escape_string($firstname);
      $lastname = $conn->real_escape_string($lastname);
      $email =  $conn->real_escape_string($email);
      //$UserGroupLogin = $conn->real_escape_string($UserGroupLogin);
         
      $sql2 = "SELECT username,email FROM userinfo WHERE username = '".$username."'";
      $res2 = $conn->query($sql2);
      $row2 = mysqli_fetch_row($res2);
      $sql3 = "SELECT email FROM userinfo WHERE email = '".$email."'";
      $res3 = $conn->query($sql3);
      $row3 = mysqli_fetch_row($res3);
          if ($row2[0] == $username and $row2[1] == $email) {
            $sql1 = "UPDATE userinfo SET firstname = '$firstname' WHERE username ='$username'";
            $sql2 = "UPDATE userinfo SET lastname = '$lastname' WHERE username = '$username'";
            $sql3 = "UPDATE userinfo SET email = '$email' WHERE username = '$username'";
            $res1 = $conn->query($sql1);
            $res2 = $conn->query($sql2);
            $res3 = $conn->query($sql3);
            $message = "แก้ไขข้อมูลสำเร็จ";
            $colorfont = "#1cc88a";
          } elseif ($row2[0] == $username and $row3[0] == NULL){
              $sql1 = "UPDATE userinfo SET firstname = '$firstname' WHERE username ='$username'";
              $sql2 = "UPDATE userinfo SET lastname = '$lastname' WHERE username = '$username'";
              $sql3 = "UPDATE userinfo SET email = '$email' WHERE username = '$username'";
              $res1 = $conn->query($sql1);
              $res2 = $conn->query($sql2);
              $res3 = $conn->query($sql3);
              $message = "แก้ไขข้อมูลสำเร็จ";
              $colorfont = "#1cc88a";
          } else {
            $message = "อีเมลล์ถูกผู้งานอื่นใช้งานแล้ว กรุณาเปลี่ยนอีเมลล์ใหม่";
            $colorfont = "#e74a3b";
          }
    }  
?>
<?php include 'layout/mainpage-header.php' ?>
<?php include 'layout/mainpage-body.php' ?>
<?php include 'layout/mainpage-menu-01.php' ?> <!-- Dashbroad -->
<?php include 'layout/mainpage-menu-02.php' ?> <!-- User Main -->
active
<?php include 'layout/mainpage-menu-03.php' ?> <!-- User Show -->
show
<?php include 'layout/mainpage-menu-04.php' ?> <!-- User Profile -->
active
<?php include 'layout/mainpage-menu-05.php' ?> <!-- User Password Change -->
<?php include 'layout/mainpage-menu-06.php' ?> <!-- Menu End -->
<?php include 'layout/mainpage-navbar.php' ?>


        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">ข้อมูลส่วนตัว</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./"><i class="fas fa-home text-primary"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">ข้อมูลส่วนตัว</li>
            </ol>
          </div>

            <div class="card card-register mx-auto mt-5">
              <div class="card-header">ข้อมูลของ คุณ 
              <?php
                $sqluser='select id, username, firstname, lastname, time from userinfo where id="'.$_SESSION["UserIDPortal"].'"';
                $resultuser = $conn->query($sqluser);
                  if ($resultuser->num_rows > 0) {
                    while($row = $resultuser->fetch_assoc()) {
                      echo $row['firstname']." ".$row['lastname'];
                    }
                  }
              ?>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <div class="form-label-group">
                    <center><?php if(isset($message,$colorfont)) { echo "<h2><font color = '".$colorfont."' > $message </font></h2>";} ?></center>
                  </div>
                </div>
                <div class="table-responsive">
                  <?php
                    $sqluser = 'select userinfo.username,firstname,lastname,email,cid,groupname from userinfo,radcheck,radusergroup where userinfo.username="'.$_SESSION["UserPortal"].'" and ((userinfo.username = radcheck.username) and (userinfo.username = radusergroup.username))';
                    $resultuser = mysqli_query($conn, $sqluser);
                    if (mysqli_num_rows($resultuser) > 0) {
                      ?>
                      <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th width = "25%"><center>ข้อมูล</center></th>
                            <th width = "75%"><center>รายละเอียด</center></th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                              while($datauser = mysqli_fetch_object($resultuser)) { 
                                $cidcut1 = substr($datauser->cid,0,6);
                                $cidcut2 = substr($datauser->cid,10,3);
                                $cidcentsor = $cidcut1."xxxx".$cidcut2;
                            ?>
                            <tr>
                              <td></td><td align="right"><a class='btn btn-primary' onclick="setdata('<?php echo  $datauser->username ?>','<?php echo $datauser->firstname ?>','<?php echo $datauser->lastname  ?>','<?php echo $datauser->email ?>')" href='#'  data-toggle='modal' data-target='#ChangeUserInfoModal' title='แก้ไขรายละเอียดส่วนตัว'><i class="fa fa-edit"></i></a></td>
                            </tr>
                            <tr>
                              <td><b>ชื่อผู้ใช้</b></td><td><?= $datauser->username ?> </td>
                            </tr>
                            <tr>
                              <td><b>ชื่อ-สกุล</b></td><td><?= $datauser->firstname ?> <?= $datauser->lastname ?> </td>
                            </tr>
                            <tr>
                              <td><b>อีเมลล์</b></td><td><?= $datauser->email ?></td>
                            </tr>
                            <tr>
                              <td><b>เลขบัตรประจำตัวประชาชน</b></td><td><?= $cidcentsor ?></td>
                            </tr>
                            <tr>
                              <td><b>กลุ่ม</b></td><td><?= $datauser->groupname?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                      </table>
                      <?php }else{ ?>
                          <div class="bs-component">
                              <div class="alert alert-danger">
                                  <strong>ขณะนี้ยังไม่มีสมาชิกในระบบ</strong>
                              </div>
                          </div>
                      <?php } ?>
                </div>
              </div>
            </div>

            
      <div class="modal fade" id="ChangeUserInfoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">แก้ไขรายละเอียดส่วนตัว</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                
                    <form action="" method='POST'>
                    <input type="text" id="firstname" name="firstname" class="form-control" placeholastnamelder="ชื่อ" required="required"><br>
                    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="นามสกุล" required="required"><br>
                    <input type="text" id="email" name="email" class="form-control" placeholder="อีเมลล์" required="required">
                    <input type="hidden" name="id" id="id">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">ยกเลิก</button>
                    <input type="submit" name="ChangeInfo" value="เปลี่ยนข้อมูลส่วนตัว" class="btn btn-primary">
                </div>
                </form>
            </div>
        </div>
      </div>
<?php include 'layout/mainpage-modal.php' ?>
<script>
  function setdata(username,firstname,lastname,email){
  $('#firstname').val(firstname);
  $('#lastname').val(lastname);
  $('#email').val(email);
  $('#id').val(username);
  }
</script>
<?php include 'layout/mainpage-footer.php' ?>
<?php include 'layout/mainpage-end.php' ?>
