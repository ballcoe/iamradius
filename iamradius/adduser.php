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

    isset($_POST['usr']) ? $username = $_POST['usr'] : $username = "";
    isset($_POST['pwd']) ? $password = $_POST['pwd'] : $passwprd = "";
    isset($_POST['firstname']) ? $firstname = $_POST['firstname'] : $firstname = "";
    isset($_POST['lastname']) ? $lastname = $_POST['lastname'] : $lastname = "";
    isset($_POST['email']) ? $email = $_POST['email'] : $email = "";
    isset($_POST['cid']) ? $CID = $_POST['cid'] : $CID = "";
    isset($_POST['group']) ? $group = $_POST['group'] : $group = "";

	  $username = $conn->real_escape_string($username);
	  $password = $conn->real_escape_string($password);
    $firstname = $conn->real_escape_string($firstname);
    $lastname = $conn->real_escape_string($lastname);
    $email = $conn->real_escape_string($email);
    $CID = $conn->real_escape_string($CID);
    $group = $conn->real_escape_string($group);
	  $datenow = date('Y-m-d H:i:s');

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
              $passwordportal = md5($passwd);
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
          $message = "ชื่อผู้ใช้เคยเป็นสมาชิกแล้ว กรุณากรอกชื่อผู้ใช้ใหม่";
          $colorfont = "#e74a3b";
      }
      
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
active
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
            <h1 class="h3 mb-0 text-gray-800">เพิ่มสมาชิก</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./"><i class="fas fa-home text-primary"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">เพิ่มสมาชิก</li>
            </ol>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header">
              <i class="fas fa-table"></i>
              เพิ่มสมาชิก
            </div>
            <div class="card-body">
              <div class="form-group">
                <div class="form-label-group">
                  <center><?php if(isset($message,$colorfont)) { echo "<h2><font color = '".$colorfont."' > $message </font></h2>";} ?></center>
                </div>
              </div>
              <form action = "" method = "POST">
                <div class="form-group">
                  <div class="form-row">
                    <div class="col-md-6">
                      <select id="selectgroup" name="group" class="select2-single-placeholder form-control">
                        <option value=""></option>
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
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="form-label-group">
                    <input type="text" id="usr" name="usr" class="form-control" placeholder="ชื่อผู้ใช้ (A-Z,a-z,0-9 เท่านั้น)" required="required" autofocus="autofocus">
                    <span id="errUsr"></span>
                  </div>
                </div>
                <div class="form-group">
                  <div class="form-label-group">
                    <input type="password" id="pwd" name="pwd" class="form-control" placeholder="รหัสผ่าน (A-Z,a-z,0-9 เท่านั้น)" required="required">
                    <span id="errPwd"></span>
                  </div>
                </div>
                <div class="form-group">
                  <div class="form-row">
                    <div class="col-md-6">
                      <div class="form-label-group">
                        <input type="text" id="firstname" name="firstname" onkeypress="return isAlpha(event);" class="form-control" placeholder="ชื่อ" required="required">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-label-group">
                        <input type="text" id="lastname" name="lastname" onkeypress="return isAlpha(event);" class="form-control" placeholder="นามสกุล" required="required">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="form-row">
                    <div class="col-md-6">
                      <div class="form-label-group">
                        <input type="email" id="email" name="email" class="form-control" placeholder="อีเมล" required="required">
                        <span id="errEmail"></span>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-label-group">
                        <input type="text" maxlength="13" onkeypress="return isNumber(event);" id="cid" name="cid" class="form-control" placeholder="เลขประจำตัวประชาชน" required="required">
                        <span id="errCID"></span>
                      </div>
                    </div>
                  </div>
                </div>
                <center><button class="btn btn-primary btn-block-30">เพิ่มสมาชิก</button></center>
              </form>
            </div>
          </div>

<?php include 'layout/mainpage-modal.php' ?>
<script>
      $('#selectgroup').select2({
        placeholder: "กรุณาเลือกกลุ่มผู้ใช้งาน",
        allowClear: true
      });      
</script>
<?php include 'layout/mainpage-footer.php' ?>
<?php include 'layout/mainpage-end.php' ?>
