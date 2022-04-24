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
active
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
            <h1 class="h3 mb-0 text-gray-800">ภาพรวม</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./"><i class="fas fa-home text-primary"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">ภาพรวม</li>
            </ol>
          </div>

          <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                <a class = "text-xs font-weight-bold text-success text-uppercase mb-1" href="viewuseronline">สมาชิกที่ใช้งานอยู่</a></div>
                                            <div class="h3 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                    $sqlOnlineList = "SELECT * FROM radacct,userinfo WHERE radacct.acctstoptime IS NULL AND radacct.username = userinfo.username ORDER BY radacct.acctstarttime";
                                                    $resultOnlineList = mysqli_query($conn, $sqlOnlineList);
                                                    if (mysqli_num_rows($resultOnlineList) > 0) {
                                                        $countOnlineList = 0;
                                                        while($dataOnlineList = mysqli_fetch_object($resultOnlineList)) { 
                                                            $countOnlineList++;
                                                        }
                                                    }else{
                                                        $countOnlineList = 0;
                                                    }
                                                    echo $countOnlineList;
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                              <a class = "text-xs font-weight-bold text-primary text-uppercase mb-1" href="viewuser">สมาชิกทั้งหมด</a></div>
                                            <div class="h3 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                                $sqlAllUser = "SELECT * FROM userinfo";
                                                $resultAllUser = mysqli_query($conn, $sqlAllUser);
                                                echo mysqli_num_rows($resultAllUser);
                                            ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                              <a class = "text-xs font-weight-bold text-info text-uppercase mb-1" href="viewgroup">กลุ่มทั้งหมด</a></div>
                                            <div class="h3 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                    $sqlAllGroup = "SELECT * FROM radgroupreply group by groupname UNION SELECT * FROM radgroupcheck";
                                                    $resultAllGroup = mysqli_query($conn, $sqlAllGroup);
                                                    echo mysqli_num_rows($resultAllGroup);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                              <a class = "text-xs font-weight-bold text-warning text-uppercase mb-1" href="userregistergroup">สมาชิกที่ยังไม่จัดกลุ่ม</a></div>
                                            <div class="h3 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                                $sqlAllUserChange = "SELECT * FROM radusergroup where groupname = 'register'";
                                                $resultAllUserChange = mysqli_query($conn, $sqlAllUserChange);
                                                echo mysqli_num_rows($resultAllUserChange);
                                            ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">จำนวนผู้ใช้งานต่อวัน</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                    <?php
                                        $arruser = [];
                                        $arrdate = [];
                                            $sqlcountuser = "select count(ra.username) as countuser , date(ra.acctstarttime) as dateuser from radacct ra group by dateuser desc limit 15";
                                            $resultuser = $conn->query($sqlcountuser);
                                            if ($resultuser->num_rows > 0) {
                                              // output data of each row
                                              while($row = $resultuser->fetch_assoc()) {
                                                $year = substr($row["dateuser"],0,4);
                                                $month = substr($row["dateuser"],5,2);
                                                $day = substr($row["dateuser"],8,2);
                                                $thdate = $day.'/'.$month.'/'.($year+543);
                                                $arruser[] = $row["countuser"];
                                                $arrdate[] = $thdate;
                                               // echo  $row["cgroup"].$row["groupname"].  "<br>";
                                              }
                                            } else {
                                              echo "";
                                            }
                                    ?>
                                    <hr>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">จำนวนสมาชิกต่อกลุ่ม</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                        <?php
                                        $arrgroupname = [];
                                        $arrtext = [];
                                        $arrcolor = [];
                                            $sqlcountgroup = "SELECT COUNT(username) AS cgroup, radusergroup.groupname as groupname , color FROM radusergroup , groupcolor WHERE groupcolor.groupname = radusergroup.groupname GROUP BY groupname";
                                            $result = $conn->query($sqlcountgroup);

                                            if ($result->num_rows > 0) {
                                              // output data of each row
                                              while($row = $result->fetch_assoc()) {
                                                $arrgroupname[] = $row["cgroup"];
                                                $arrtext[] = $row["groupname"];
                                                $arrcolor[] = $row["color"];
                                               // echo  $row["cgroup"].$row["groupname"].  "<br>";
                                              }
                                            } else {
                                              echo "";
                                            }
                                        ?>
                                    <hr>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                        ชี้ที่สีเพื่อดูจำนวนสมาชิกของกลุ่ม
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

<?php include 'layout/mainpage-modal.php' ?>
<script type="text/javascript">
      // Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctxpie = document.getElementById("myPieChart");
//const randomNum = () => Math.floor(Math.random() * (235 - 52 + 1) + 52);

//const randomRGB = () => `rgb(${randomNum()}, ${randomNum()}, ${randomNum()})`;

var myPieChart = new Chart(ctxpie, {
  type: 'doughnut',
  data: {
    labels: <?php echo json_encode($arrtext) ?>,
    datasets: [{
      data: <?php echo json_encode($arrgroupname) ?>,
      backgroundColor: <?php echo json_encode($arrcolor) ?>,
      hoverBackgroundColor: <?php echo json_encode($arrcolor) ?>,
    //  backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
    //  hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 80,
  },
});  

// Set new default font family and font color to mimic Bootstrap's default styling

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($arrdate) ?>,
    datasets: [{
      label: "จำนวนผู้ใช้งาน",
      lineTension: 0.3,
      backgroundColor: "rgba(78, 115, 223, 0.05)",
      borderColor: "rgba(78, 115, 223, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(78, 115, 223, 1)",
      pointBorderColor: "rgba(78, 115, 223, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
      pointHoverBorderColor: "rgba(78, 115, 223, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      data: <?php echo json_encode($arruser) ?>,
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5,
          padding: 10,
          // Include a dollar sign in the ticks
          callback: function(value, index, values) {
            return number_format(value) + ' ผู้ใช้';
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: false
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ': ' + number_format(tooltipItem.yLabel) + ' ผู้ใช้';
        }
      }
    }
  }
});

</script>   
<?php include 'layout/mainpage-footer.php' ?>
<?php include 'layout/mainpage-end.php' ?>
