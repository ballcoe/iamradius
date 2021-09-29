<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="../img/undraw_profile.svg" rel="icon">
  <title>ระบบจัดการสมาชิก <?php
    $sqlsystemname = "select * from systemname";
    $resultsystemname = $conn->query($sqlsystemname);
    if ($resultsystemname->num_rows > 0) {
      while($row = $resultsystemname->fetch_assoc()) {
        echo $row['name'];
      }
    } ?></title>
  <link href="../vendor/fontawesome-free/css/all.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="../css/ruang-admin.css" rel="stylesheet">
  <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../vendor/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css">
</head>
