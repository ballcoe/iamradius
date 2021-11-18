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
$sql = "select userinfo.username,firstname,lastname,email,cid,groupname from userinfo left join radcheck on (userinfo.username = radcheck.username) left join radusergroup on (userinfo.username = radusergroup.username)";
$result = mysqli_query($con, $sql);
$i=0;
$array = [];
while($row = mysqli_fetch_assoc($result)) {
    $i++;
    $cidcut1 = substr($row["cid"],0,6);
    $cidcut2 = substr($row["cid"],10,3);
    $cidcentsor = $cidcut1."xxxx".$cidcut2;
    $array[] = [
       'num'=> $i,
       'username'=> $row["username"],
       'fullname'=> $row["firstname"]." ".$row["lastname"],
       'email'=> $row["email"],
       'cid'=> $cidcentsor,
       'groupname'=> $row["groupname"]
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