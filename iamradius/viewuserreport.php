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
<?php include 'layout/mainpage-menu-15.php' ?> <!-- View User Report -->
active
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
            <h1 class="h3 mb-0 text-gray-800">ประวัติการใช้งาน</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./"><i class="fas fa-home text-primary"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">ประวัติการใช้งาน</li>
            </ol>
          </div>
            <div class="card shadow mb-4">
              <div class="card-header">
                <i class="fas fa-table"></i>
                ประวัติการใช้งาน
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-xl-2 col-lg-3">
                    กรุณาเลือกผู้ใช้งาน : 
                  </div>
                  <div class="col-xl-4 col-lg-3">
                    <select id="selectusername" name="username" class="select2-single-placeholder form-control" onchange="changeusername()">
                        <option value=""></option>
                        <?php   
                          $sql2 ='SELECT userinfo.username FROM userinfo group by userinfo.username';
                          $result2 = $conn->query($sql2);
                          if ($result2->num_rows > 0) {
                            // output data of each row
                            while($row2 = $result2->fetch_assoc()) {
                              echo "<option value='" . $row2["username"]. "'>" . $row2["username"]. "</option>";
                            }
                          } else {
                            echo "0 results";
                          }
                        ?>
                    </select>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-xl-12 col-lg-3">
                    <div class="table-responsive">
                      <div class="datashow1"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>


<?php include 'layout/mainpage-modal.php' ?>
<script>
  $('#selectusername').select2({
    placeholder: "กรุณาเลือกผู้ใช้งาน",
    allowClear: true
  });
  //changeusername();
  $('#selectusername').on('change keyup paste', function() {
    $(".datashow1").html('<center>กำลังโหลดข้อมูล.......</center>');
  });
  function changeusername(){
    var selectusername = $('#selectusername').val();
    //console.log(selectusername)
    $.ajax({
      url: "showdatareport.php?selectusername="+selectusername,
      cache: false,
      success: function(html){
        $(".datashow1").html(html);
      }
    });
  //console.log($('#selectusername').val());
  }
</script>
<?php include 'layout/mainpage-footer.php' ?>
<?php include 'layout/mainpage-end.php' ?>
