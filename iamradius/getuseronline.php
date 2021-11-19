<?php
session_start();
include("config.php");

// db connection
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Error " . mysqli_error($con));
if (!$_SESSION["UserID"]){ 	
    $sql='select id, username, time from operator where address="'.$_SERVER["REMOTE_ADDR"].'"';		
    $stmt = $con->prepare($sql);	
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
// fetch records
$sql = "SELECT * FROM radacct,userinfo WHERE radacct.acctstoptime IS NULL AND radacct.username = userinfo.username ORDER BY radacct.acctstarttime DESC";
$result = mysqli_query($con, $sql);
$i=0;
$array = [];
while($row = mysqli_fetch_assoc($result)) {
    $i++;
    $year = substr($row["acctstarttime"],0,4);
    $month = substr($row["acctstarttime"],5,2);
    $day = substr($row["acctstarttime"],8,2);
    $thdate = $day.'/'.$month.'/'.($year+543);
    $thtime = substr($row["acctstarttime"],11,8);
    $thdatetime = "วันที่ ".$thdate." เวลา ".$thtime." น." ;
    $array[] = [
       'num'=> $i,
       'username'=> $row["username"],
       'fullname'=> $row["firstname"]." ".$row["lastname"],
       'ip'=> $row["framedipaddress"] ,
       'mac'=> $row["callingstationid"],
       'date'=> $thdatetime
    ];
    //$array[] = $row;
}

$dataset = [
    "success" => 1,
    "totalrecords" => count($array),
    "totaldisplayrecords" => count($array),
    "data" => $array
    ];

echo json_encode($dataset);
?>