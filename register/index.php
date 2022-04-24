<?php
include("../iamradius/config.php");

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
mysqli_set_charset($conn, DB_CHARSET);

if (count($_POST) > 0) {
  isset($_POST['usr']) ? $username = $_POST['usr'] : $username = "";
  isset($_POST['pwd']) ? $passwd = $_POST['pwd'] : $passwd = "";
  isset($_POST['cpwd']) ? $passwdc = $_POST['cpwd'] : $passwdc = "";
  isset($_POST['firstname']) ? $firstname = $_POST['firstname'] : $firstname = "";
  isset($_POST['lastname']) ? $lastname = $_POST['lastname'] : $lastname = "";
  isset($_POST['email']) ? $email = $_POST['email'] : $email = "";
  isset($_POST['cid']) ? $CID = $_POST['cid'] : $CID = "";

  $username = $conn->real_escape_string($username);
	$password = $conn->real_escape_string($password);
  $firstname = $conn->real_escape_string($firstname);
  $lastname = $conn->real_escape_string($lastname);
  $email = $conn->real_escape_string($email);
	$CID = $conn->real_escape_string($CID);
	$datenow = date('Y-m-d H:i:s');

  $sql = "SELECT * FROM radcheck WHERE Username = '".$username."'";
  $res = $conn->query($sql);
	$row = mysqli_fetch_row($res);

	if (!$row) {
		$sql = "SELECT * FROM userinfo WHERE cid = '".$CID."'";
    $res = $conn->query($sql);
		$row = mysqli_fetch_row($res);
    if (!$row) {
      $sql = "SELECT * FROM userinfo WHERE email = '".$email."'";
      $res = $conn->query($sql);
			$row = mysqli_fetch_row($res);
			if (!$row) {
				$password = $passwd;
        $passwordportal = md5($passwd);
        $sql = "INSERT INTO radcheck (id, Username, Attribute, op, Value) VALUES (0, '$username', 'Cleartext-Password', ':=', '$password')";
        $res = $conn->query($sql);
        /* adding user information to the userinfo table */
        $sql = "INSERT INTO userinfo (username, firstname, lastname, email, cid, date_regis ,passwordportal) VALUES ('$username', '$firstname', '$lastname', '$email', '$CID' , '$datenow' , '$passwordportal')";
        $res = $conn->query($sql);
        /* adding the user to the default group defined */
        $sql = "INSERT INTO radusergroup (UserName, GroupName, priority) VALUES ('$username', 'Register', '0')";
        $res = $conn->query($sql);
        echo"<script language=\"JavaScript\">";
        echo"alert('สมัครสมาชิกสำเร็จ');";
        echo"window.location.href='http://www.gstatic.com/generate_204';";
        echo"</script>";
			} else {
        echo"<script language=\"JavaScript\">";
        echo"alert('ที่อยู่อีเมลเคยสมาชิกแล้ว กรุณาติดต่อผู้ดูแลระบบ');";
        echo"window.location.href='./';";
        echo"</script>";
			}                                          
    } else {
      echo"<script language=\"JavaScript\">";
      echo"alert('เลขบัตรประจำตัวประชาชนเคยสมาชิกแล้ว กรุณาติดต่อผู้ดูแลระบบ');";
      echo"window.location.href='./';";
      echo"</script>";
		}
	} else {
    echo"<script language=\"JavaScript\">";
    echo"alert('ชื่อผู้ใช้เคยเป็นสมาชิกแล้ว กรุณากรอกชื่อผู้ใช้ใหม่');";
    echo"window.location.href='./';";
    echo"</script>";
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="../img/undraw_profile.svg" rel="icon">
  <title>ระบบสมัครสมาชิก <?php
    $sqlsystemname = "select * from systemname";
    $resultsystemname = $conn->query($sqlsystemname);
    if ($resultsystemname->num_rows > 0) {
      while($row = $resultsystemname->fetch_assoc()) {
        echo $row['name'];
      }
    } ?></title>
    
  <link href="../vendor/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="../css/ruang-admin.css" rel="stylesheet">
  
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
                        <h1 class="h3 text-gray-900 mb-4">ระบบสมัครสมาชิก <?php
                        $sqlsystemname = "select * from systemname";
                        $resultsystemname = $conn->query($sqlsystemname);
                        if ($resultsystemname->num_rows > 0) {
                        while($row = $resultsystemname->fetch_assoc()) {
                            echo $row['name'];
                        }
                        } ?></h1>
                    </div>
                    <form action = "" method = "POST">
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="text" id="usr" name="usr" class="form-control" placeholder="ชื่อผู้ใช้ (A-Z,a-z,0-9 เท่านั้น)" required="required" autofocus="autofocus">
                            <span id="errUsr"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-label-group">
                                    <input type="password" id="pwd" name="pwd" class="form-control" placeholder="รหัสผ่าน (A-Z,a-z,0-9 เท่านั้น)" required="required">
                                    <span id="errPwd"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-group">
                                    <input type="password" id="cpwd" name="cpwd" class="form-control" placeholder="รหัสผ่าน (ยืนยัน)" required="required">
                                    <span id="errcPwd"></span>
                                </div>
                            </div>
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
                        <div class="form-label-group">
                            <input type="email" id="email" name="email" class="form-control" placeholder="อีเมล" required="required">
                            <span id="errEmail"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="text" maxlength="13" onkeypress="return isNumber(event);" id="cid" name="cid" class="form-control" placeholder="เลขประจำตัวประชาชน" required="required">
                            <span id="errCID"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6" align="center">
                                <div style = " width: 250px;
                                height: 40px;
                                -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
                                box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
                                -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
                                -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                                transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                                border: 1px solid #CCC;">
                                    <div style="text-shadow: 4px 4px 5px rgba(150, 150, 150, 1);
                                    color: #2d2d2d;
                                    text-align: center;
                                    font-size: 28px;
                                    font-weight: 900;
                                    -webkit-touch-callout: none; /* iOS Safari */
                                    -webkit-user-select: none;   /* Chrome/Safari/Opera */
                                    -khtml-user-select: none;    /* Konqueror */
                                    -moz-user-select: none;      /* Firefox */
                                    -ms-user-select: none;       /* Internet Explorer/Edge */
                                    user-select: none;           /* Non-prefixed version, currently */
                                    cursor: help;">
                                        <div class="dynamic-code">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-group">
                                    <input type="text" class="form-control" id="captcha-input" required="required" autocomplete="off"  placeholder="กรอกตัวอักษรที่ปรากฏ">
                                    <span id="errCaptcha"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">                       
                        <center><button type="submit" class="btn btn-primary btn-block-30">สมัครสมาชิก</button></center>
                    </div>
                    </form>
                    <div class="text-center">
                        <br><a href="http://www.gstatic.com/generate_204"><h5>เข้าสู่ระบบ</h5></a>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h6>ศูนย์สารสนเทศ <?php
                        $sqlsystemname = "select * from systemname";
                        $resultsystemname = $conn->query($sqlsystemname);
                        if ($resultsystemname->num_rows > 0) {
                        while($row = $resultsystemname->fetch_assoc()) {
                            echo $row['name'];
                        }
                        } ?></h6>
                    </div>
                    <div class="text-center">
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
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../js/ruang-admin.min.js"></script>
  <script src="../js/captcha.js"></script>
</body>

</html>