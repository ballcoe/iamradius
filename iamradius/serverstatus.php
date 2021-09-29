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
<?php include 'layout/mainpage-menu-13.php' ?> <!-- Report Show -->
<?php include 'layout/mainpage-menu-14.php' ?> <!-- View User Online -->
<?php include 'layout/mainpage-menu-15.php' ?> <!-- Top 10 User -->
<?php include 'layout/mainpage-menu-16.php' ?> <!-- Maintanance Main -->
active
<?php include 'layout/mainpage-menu-17.php' ?> <!-- Maintanance Show -->
show
<?php include 'layout/mainpage-menu-18.php' ?> <!-- Change Name Orga -->
<?php include 'layout/mainpage-menu-19.php' ?> <!-- Server Status -->
active
<?php include 'layout/mainpage-menu-20.php' ?> <!-- PHPMYADMIN -->
<?php include 'layout/mainpage-menu-21.php' ?> <!-- Menu End-->
<?php include 'layout/mainpage-navbar.php' ?> 


        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">สถานะของเซิร์ฟเวอร์</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./"><i class="fas fa-home text-primary"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">สถานะของเซิร์ฟเวอร์</li>
            </ol>
          </div>
        <div class="card card-register mx-auto mt-5">
          <div class="card-body">
          <!--<h1>กำลังพัฒนา</h1>-->
          <div>
            <center>
            
<?php
function uptime() {
        $file_name = "/proc/uptime";

        $fopen_file = fopen($file_name, 'r');
        $buffer = explode(' ', fgets($fopen_file, 4096));
        fclose($fopen_file);

        $sys_ticks = trim($buffer[0]);
        $min = $sys_ticks / 60;
        $hours = $min / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));
        $result = "";

        if ($days != 0) {
                if ($days > 1)
                        $result = "$days " . " days ";
                else
                        $result = "$days " . " day ";
        }

        if ($hours != 0) {
                if ($hours > 1)
                        $result .= "$hours " . " hours ";
                else
                        $result .= "$hours " . " hour ";
        }

        if ($min > 1 || $min == 0)
                $result .= "$min " . " minutes ";
        elseif ($min == 1)
                $result .= "$min " . " minute ";

        return $result;
}


// Display hostname system
// @return string System hostname or none
function get_hostname() {
        $file_name = "/proc/sys/kernel/hostname";

        if ($fopen_file = fopen($file_name, 'r')) {
                $result = trim(fgets($fopen_file, 4096));
                fclose($fopen_file);
        } else {
                $result = "(none)";
        }

        return $result;
}


// Display currenty date/time
// @return string Current system date/time or none
function get_datetime() {
        if ($today = date("F j, Y, g:i a")) {
                $result = $today;
        } else {
                $result = "(none)";
        }

        return $result;
}



// Get System Load Average
// @return array System Load Average
function get_system_load() {
        $file_name = "/proc/loadavg";
        $result = "";
        $output = "";

        // get the /proc/loadavg information
        if ($fopen_file = fopen($file_name, 'r')) {
                $result = trim(fgets($fopen_file, 256));
                fclose($fopen_file);
        } else {
                $result = "(none)";
        }

        $loadavg = explode(" ", $result);
        $output .= $loadavg[0] . " " . $loadavg[1] . " " . $loadavg[2] . "<br/>";


        // get information the 'top' program
        $file_name = "top -b -n1 | grep \"Tasks:\" -A1";
        $result = "";

        if ($popen_file = popen($file_name, 'r')) {
                $result = trim(fread($popen_file, 2048));
                pclose($popen_file);
        } else {
                $result = "(none)";
        }

        $result = str_replace("\n", "<br/>", $result);
        $output .= $result;

        return $output;
}


// Get Memory System MemTotal|MemFree
// @return array Memory System MemTotal|MemFree
function get_memory() {
        $file_name = "/proc/meminfo";
        $mem_array = array();

        $buffer = file($file_name);

        while (list($key, $value) = each($buffer)) {
                if (strpos($value, ':') !== false) {
                        $match_line = explode(':', $value);
                        $match_value = explode(' ', trim($match_line[1]));
                        if (is_numeric($match_value[0])) {
                                $mem_array[trim($match_line[0])] = trim($match_value[0]);
                        }
                }
        }

        return $mem_array;
}


//Get FreeDiskSpace
function get_hdd_freespace() {
$df = disk_free_space("/");
return $df;
}


// Convert value to MB
// @param decimal $value
// @return int Memory MB
function convert_ToMB($value) {
        return round($value / 1024) . " MB\n";
}



// Get all network names devices (eth[0-9])
// @return array Get list network name interfaces
function get_interface_list() {
        $devices = array();
        $file_name = "/proc/net/dev";

        if ($fopen_file = fopen($file_name, 'r')) {
                while ($buffer = fgets($fopen_file, 4096)) {
                        if (preg_match("/eth[0-9][0-9]*/i", trim($buffer), $match) or preg_match("/ens[0-9][0-9]*/i", trim($buffer), $match)) {
                                $devices[] = $match[0];
                        }
                }
                $devices = array_unique($devices);
                sort($devices);
                fclose ($fopen_file);
        }
        return $devices;
}



// Get ip address
// @param string $ifname
// @return string Ip address or (none)
function get_ip_addr($ifname) {
        $command_name = "/sbin/ifconfig $ifname";
        $ifip = "";

        exec($command_name , $command_result);

        $ifip = implode($command_result, "\n");
        if (preg_match("/inet addr:[0-9\.]*/i", $ifip, $match)) {
                $match = explode(":", $match[0]);
                return $match[1];
        } elseif (preg_match("/inet [0-9\.]*/i", $ifip, $match)) {
                $match = explode(" ", $match[0]);
                return $match[1];
        } else {
                return "(none)";
        }
}

// Get mac address
// @param string $ifname
// @return string Mac address or (none)
function get_mac_addr($ifname) {
        $command_name = "/sbin/ifconfig $ifname";
        $ifip = "";

        exec($command_name , $command_result);

        $ifmac = implode($command_result, "\n");
        if (preg_match("/hwaddr [0-9A-F:]*/i", $ifmac, $match)) {
                $match = explode(" ", $match[0]);
                return $match[1];
        } elseif (preg_match("/ether [0-9A-F:]*/i", $ifmac, $match)) {
                $match = explode(" ", $match[0]);
                return $match[1];
        } else {
                return "(none)";
        }
}


// Get netmask address
// @param string $ifname
// @return string Netmask address or (none)
function get_mask_addr($ifname) {
        $command_name = "/sbin/ifconfig $ifname";
        $ifmask = "";

        exec($command_name , $command_result);

        $ifmask = implode($command_result, "\n");
        if (preg_match("/mask:[0-9\.]*/i", $ifmask, $match)) {
                $match = explode(":", $match[0]);
                return $match[1];
        } elseif (preg_match("/netmask [0-9\.]*/i", $ifmask, $match)) {
                $match = explode(" ", $match[0]);
                return $match[1];
        } else {
                return "(none)";
        }
}

?>


<?php
        echo "<h3>General Information</h3>";
?>

<table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
  <tr>
    <td width="20%"> Uptime </td>
    <td><span class='sleft'><?php echo uptime(); ?></span> </td>
  </tr>
  <tr>
    <td width="20%"> System Load </td>
    <td><span class='sleft'><?php echo get_system_load(); ?></span> </td>
  </tr>
  <tr>
    <td width="20%"> Hostname </td>
    <td><span class='sleft'><?php echo get_hostname(); ?></span> </td>
  </tr>
  <tr>
    <td width="20%"> Current Date </td>
    <td><span class='sleft'><?php echo get_datetime(); ?></span> </td>
  </tr>
</table>


<?php
        echo "<h3>Memory Information</h3>";
        $meminfo = get_memory();
?>


<table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
  <tr>
    <td width="20%"> Mem. Total </td>
    <td><span class='sleft'><?php echo convert_ToMB ($meminfo['MemTotal']); ?></span> </td>
  </tr>
  <tr>
    <td width="20%"> Mem. Free </td>
    <td><span class='sleft'><?php echo convert_ToMB ($meminfo['MemFree']); ?></span> </td>
  </tr>
  <tr>
    <td width="20%"> Mem. Used </td>
    <td>
                <span class='sleft'>
                        <?php
                                $memused = ($meminfo['MemTotal'] - $meminfo['MemFree']);
                                echo convert_ToMB ($memused);
                        ?>
                </span> </td>
  </tr>
</table>


<?php
        echo "<h3>Harddrive Information</h3>";
        $hddfreespace = get_hdd_freespace();
        $si_prefix = array( 'B', 'KiB', 'MiB', 'GiB', 'TiB', 'EB', 'ZB', 'YB' );
        $base = 1024;
        $class = min((int)log($hddfreespace , $base) , count($si_prefix) - 1);
?>


<table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
  <tr>
    <td width="20%"> Free Drive Space </td>
    <td><?php echo sprintf('%1.2f' , $hddfreespace / pow($base,$class)) . ' ' . $si_prefix[$class] . '<br />';?> </td>
  </tr>

</table>

<?php
        echo "<h3>Network Interfaces</h3>";
        $iflist = get_interface_list();

        foreach ($iflist as $ifname) {
                        echo "\t<table class='table table-bordered' id='dataTable2' width='100%'' cellspacing='0'>\n";
                        echo "\t<tr>\n";
                        echo "\t\t<td width='20%'>Interface Name</td>\n";
                        echo "\t\t<td>\n";
                        echo "\t\t\t$ifname\n";
                        echo "\t\t</td>\n";
                        echo "\t</tr>\n";
                        echo "\t<tr>\n";
                        echo "\t<tr>\n";
                        echo "\t\t<td>\n";
                        echo "\t\t\tIp Address\n";
                        echo "\t\t</td>\n";
                        echo "\t\t<td>\n";
                        echo "\t\t\t".get_ip_addr($ifname)."\n";
                        echo "\t\t</td>\n";
                        echo "\t</tr>\n";
                        echo "\t<tr>\n";
                        echo "\t\t<td>\n";
                        echo "\t\t\tSubnet Mask\n";
                        echo "\t\t</td>\n";
                        echo "\t\t<td>\n";
                        echo "\t\t\t".get_mask_addr($ifname)."\n";
                        echo "\t\t</td>\n";
                        echo "\t</tr>\n";
                        echo "\t<tr>\n";
                        echo "\t\t<td>\n";
                        echo "\t\t\tMAC address\n";
                        echo "\t\t<td>\n";
                        echo "\t\t\t".get_mac_addr($ifname)."\n";
                        echo "\t\t</td>\n";
                        echo "\t</tr>\n";
                }

        echo "\t</table>\n";

function check_service($sname) {
	if ($sname != '') {
		system("pgrep ".escapeshellarg($sname)." >/dev/null 2>&1", $ret_service);
		if ($ret_service == 0) {
			return "Enabled";
		} else {
			return "Disabled";
		}
	} else {
		return "no service name";
	}
}

?>

<?php
	echo "<h3>Service Status</h3>";
?>

<table class='table table-bordered' id='dataTable2' width='100%'' cellspacing='0'>
  <tr>
    <td width='20%'> Radius </td>
    <td><span class='sleft'><?php echo check_service("radius"); ?></span> </td>
  </tr>
  <tr>
    <td width='20%'> Mysql </td>
    <td><span class='sleft'><?php echo check_service("mysql");  ?></span> </td>
  </tr>
</table>

            </center>
        </div>
<?php include 'layout/mainpage-modal.php' ?>
<?php include 'layout/mainpage-footer.php' ?>
<?php include 'layout/mainpage-end.php' ?>
