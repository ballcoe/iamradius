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
$sql = "select rp1.groupname,(select value from radgroupreply rp2 where rp2.groupname = rp1.groupname AND rp2.attribute = 'WISPr-Bandwidth-Max-Up' ) AS upbandwidth,
(select value from radgroupreply rp3 where rp3.groupname = rp1.groupname AND rp3.attribute = 'WISPr-Bandwidth-Max-Down' ) AS downbandwidth,
(select value from radgroupreply rp4 where rp4.groupname = rp1.groupname AND rp4.attribute = 'Port-Limit' ) AS PortLimit , color
from radgroupreply rp1,groupcolor where rp1.groupname = groupcolor.groupname GROUP by rp1.groupname";
$result = mysqli_query($con, $sql);
$i=0;
$array = [];
while($row = mysqli_fetch_assoc($result)) {
    $i++;
    $upload = $row["upbandwidth"] / 1024;
    $download = $row["downbandwidth"] / 1024;
    $array[] = [
       'num'=> $i,
       'groupname'=> $row["groupname"],
       'upload'=> $upload,
       'download'=> $download,
       'portlimit'=> $row["PortLimit"],
       'color' => $row["color"]
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