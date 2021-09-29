<?php
	session_start();
	include("config.php");
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);} 
    $conn -> set_charset("utf8");

    $sqlfail='select fail_id, fail_ip, fail_try, time from failip where fail_ip="'.$_SERVER['REMOTE_ADDR'].'"';
    $stmtf = $conn->prepare($sqlfail);	
	$stmtf->execute();
	$stmtf->store_result();
	$stmtf->bind_result($fail_id, $fail_ip, $fail_try, $ftimestamp);
    if ($stmtf->num_rows > 0) {
        while ($stmtf->fetch()){
            $ofail = True;
            $ofail_id = $fail_id;
            $ofail_try = $fail_try;
        }
    } else {
        $ofail = False;
    }
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    
    $sql1 = "select * from operator where username = '$username' and password = '$password'";
    $result1 = $conn->query($sql1);

    if ($result1->num_rows > 0) {
        // output data of each row
        while($row = $result1->fetch_assoc()) {       
            $_SESSION["UserID"] = $row["id"];
            $_SESSION["User"] = $row["username"];
            $sqladdr='update operator set address="'.$_SERVER["REMOTE_ADDR"].'", time="'.time().'" WHERE id = "'.$row["id"].'"';	
			$conn->query($sqladdr);
			if ($ofail) {
				$sqlfailup='update failip set fail_try="0", time="'.time().'" WHERE fail_id = "'.$ofail_id.'"';
			} else {
		    	$sqlfailup='insert into failip (fail_ip, fail_try, time, username) values ("'.$_SERVER["REMOTE_ADDR"].'","0","'.time().'","'.$username.'")';	
			}
			$conn->query($sqlfailup);
            Header("Location: ./");
        }
    } else {
        if ($ofail) {
            $sqlfailup='update failip set fail_try="'.($ofail_try +1).'", time="'.time().'" WHERE fail_id = "'.$ofail_id.'"';	
        } else {
            $sqlfailup='insert into failip (fail_ip, fail_try, time, username) values ("'.$_SERVER['REMOTE_ADDR'].'","1","'.time().'","'.$username.'")';	
        }
        $conn->query($sqlfailup);
        Header("Location: login");
    }
    $stmtf->free_result();
	$stmtf->close();
    $conn->close();
?>