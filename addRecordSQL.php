<?php
  // Load in URL parameters
  $year = $_POST["year"];
  $commodity = $_POST["commodity"];
  $county = $_POST["county"];
  $measurement = $_POST["measurement"];
  $value = $_POST["value"];

  $log = "";

  // Connect to the database
  $mysqli = new mysqli("mysql.cis.ksu.edu", "colecoop", "insecurepassword", "colecoop");
  $mysqli->autocommit(FALSE); // Turn off autocommits
  
  // Get the MID number. If it doesn't exist, create it and get the MID.  
  $query_ok = TRUE;
  $result = $mysqli->query("SELECT mid FROM agcom_measurement WHERE measurement = '$measurement'");
  if ($row = $result->fetch_assoc())  {
    $mid = $row["mid"];
    $log .= "Measurement '$measurement' with MID '$mid' pulled from database.<br>";
  } else {
    $query_ok = TRUE; 
    $mysqli->query("INSERT INTO agcom_measurement (measurement) VALUES ('$measurement')") ? NULL : $query_ok = FALSE;
    if ($query_ok) {
      $log .= "Measurement '$measurement' added to database.<br>";
      $mysqli->commit();
    } else {
      $mysqli->rollback();
      $log .= "Unable to add measurement '$measurement' to the database.<br>";
      continue;
    }

    $result = $mysqli->query("SELECT mid FROM agcom_measurement WHERE measurement = '$data[3]'");
    $row = $result->fetch_row();
    $mid = $row[0];
  }
 
  // Insert the values into the commodities table
  $query_ok = TRUE;
  $mysqli->query("INSERT INTO agcom_commodities (year, CountyName, type, mid, value) VALUES ($year, '$county', '$commodity', $mid, $value)") ? NULL : $query_ok = FALSE;

  if ($query_ok) {
    $log .= "Record added to database.<br>";
    $mysqli->commit();
  } else {
    $mysqli->rollback();
    $log .= "Unable to add record to database.<br>";
  }

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>Add New Record - Kansas Counties Agricultural Output</title>

  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="container">
  <div id="header">
    <a href="index.php">Kansas Counties Agricultural Output</a>
  </div>
  
  <a href="addRecord.php">(( Add Another Record ))</a>&nbsp;&nbsp;
  <a href="index.php">(( Home ))</a><br>
  <div class="controls">
    <br><br>
  </div>
  <?php
    print $log;
  ?>
  <div class="controls">
    <br><br>
  </div>
</div>

</body>
