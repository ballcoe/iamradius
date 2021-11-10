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
                <?php
                  $selectusername = $_GET['selectusername'];
                  $sqlOnlineList = "SELECT * FROM radacct,userinfo WHERE radacct.username = '$selectusername' AND radacct.username = userinfo.username ORDER BY radacct.acctstarttime DESC";
                  $resultOnlineList = mysqli_query($conn, $sqlOnlineList);
                  if (mysqli_num_rows($resultOnlineList) > 0) {
                ?>
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><center>ลำดับ</center></th>
                                <th><center>ชื่อผู้ใช้</center></th>
                                <th><center>ชื่อ-สกุล</center></th>
                                <th><center>Mac-Adddress</center></th>
                                <th><center>เวลาเริ่มใช้งาน</center></th>
                                <th><center>เวลาสิ้นสุดการใช้งาน</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $countOnlineList = 0;
                                while($row = $resultOnlineList->fetch_assoc()){
                                    $countOnlineList++;
                                    $year1 = substr($row["acctstarttime"],0,4);
                                    $month1 = substr($row["acctstarttime"],5,2);
                                    $day1 = substr($row["acctstarttime"],8,2);
                                    $thdate1 = $day1.'/'.$month1.'/'.($year1+543);
                                    $thtime1 = substr($row["acctstarttime"],11,8);
                                    $thdatetime1 = "วันที่ ".$thdate1." เวลา ".$thtime1." น." ;
                                    $year2 = substr($row["acctstarttime"],0,4);
                                    $month2 = substr($row["acctstarttime"],5,2);
                                    $day2 = substr($row["acctstarttime"],8,2);
                                    $thdate2 = $day2.'/'.$month2.'/'.($year2+543);
                                    $thtime2 = substr($row["acctstarttime"],11,8);
                                    $thdatetime2 = "วันที่ ".$thdate2." เวลา ".$thtime2." น." ;
                            ?>
                            <tr>
                                <td><center><?= $countOnlineList  ?></center></td>
                                <td><center><?= $row["username"] ?></center> </td>
                                <td><center><?= $row["firstname"]." ".$row["lastname"] ?></center> </td>
                                <td><center><?= $row["callingstationid"] ?></center> </td>
                                <td><center><?= $thdatetime1 ?></center></td>
                                <td><center><?= $thdatetime2 ?></center></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php }else{ ?>
                        <div class="bs-component">
                            <div class="alert alert-danger">
                                <strong>สมาชิกที่ท่านเลือกยังไม่ได้มีการใช้งานในระบบ</strong>
                            </div>
                        </div>
                    <?php } ?>
              

                    <script>
                          $('#dataTable').dataTable();
                    </script>