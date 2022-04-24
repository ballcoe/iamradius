<?php
  session_start();
  //session_destroy();
  include("config.php");
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);} 
  mysqli_set_charset($conn, DB_CHARSET);
  $sqlfail='select fail_id, fail_ip, fail_try, time from failip where fail_ip="'.$_SERVER['REMOTE_ADDR'].'"';
	$stmtf = $conn->prepare($sqlfail);	
	$stmtf->execute();
	$stmtf->store_result();
  $stmtf->bind_result($fail_id, $fail_ip, $fail_try, $ftimestamp);
  if ($stmtf->num_rows > 0) {
		while ($stmtf->fetch()){
			if ($fail_try > $fail_allow){
				if ((time()- $ftimestamp) > (($fail_try - $fail_allow) * $fail_wait)){
					$ban = False;
				} else {
					$ban = True;
					$remain = ((($fail_try - $fail_allow) * $fail_wait)+$ftimestamp) - time();
				}
			} else {
				$ban = False;
			}
		}
	} else {
		$ban = False;
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
  <title>ระบบจัดสมาชิก
  <?php
      $sqlsystemname = "select * from systemname";
      $resultsystemname = $conn->query($sqlsystemname);
      if ($resultsystemname->num_rows > 0) {
          while($row = $resultsystemname->fetch_assoc()) {
              echo $row['name'];
          }
      }
  ?></title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="../css/ruang-admin.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-login">
  <!-- Login Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">กรุณาเข้าสู่ระบบ</h1>
                  </div>
                  <?php
                    if ($ban) {
                      echo "<center><font color='#e74a3b'><span id=timeLeft>ท่านใส่รหัสผ่านไม่ถูกต้อง กรุณารอ  ".$remain." วินาที</span></font></center><br>";
                    } else {
                      echo "";					
					          }
                  ?>
                  <form class="user" action="checklogin.php" method = "POST" >
                    <div class="form-group">
                      <input type="text" class="form-control" id="username" name="username" required="required" autofocus="autofocus" placeholder="ชื่อผู้ใช้" >
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" id="password" name="password" required="required" placeholder="รหัสผ่าน">
                    </div>
                    <div class="form-group">
                    <button id="submitButton"
                        <?php
                            if($ban){
                            echo 'disabled="disabled"';
                            ?>
                            class='btn btn-secondary btn-block'
                            <?php
                            }else{
                            ?>
                            class='btn btn-primary btn-block'
                            <?php
                            }
                        ?>
                        >เข้าสู่ระบบ</button>
                    </div>
                  </form>
                  <hr>
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
  <!-- Login Content -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../js/ruang-admin.min.js"></script>
</body>

</html>